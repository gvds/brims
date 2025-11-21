<div x-cloak x-data="tusUploader()" class="w-full">

    <!-- Incomplete Uploads Section -->
    <div class="mb-5 p-4 border border-orange-300 rounded-md bg-orange-50" x-show="incompleteUploads.length > 0" style="display: none;">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-orange-800">
                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                Incomplete Uploads Found
            </h3>
            <button
                type="button"
                @click="clearIncompleteUploads()"
                class="text-xs px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors"
            >
                Clear All
            </button>
        </div>
        <p class="text-sm text-orange-700 mb-3">The following uploads were not completed. You can resume them:</p>
        <div class="space-y-2">
            <template x-for="incomplete in incompleteUploads" :key="incomplete.uploadUrl">
                <div class="flex justify-between items-center p-3 bg-white border border-orange-200 rounded">
                    <div class="flex-1 text-gray-500">
                        <div class="font-medium text-sm" x-text="incomplete.metadata.filename || 'Unknown file'"></div>
                        <div class="text-xs">
                            <span x-text="incomplete.size > 0 ? formatBytes(incomplete.size) : 'Size unknown'"></span>
                            <span x-show="incomplete.offset && incomplete.size > 0"> - <span x-text="Math.round((incomplete.offset / incomplete.size) * 100)"></span>% uploaded</span>
                        </div>
                        <div class="text-xs text-gray-400 mt-1" x-text="'URL: ' + incomplete.uploadUrl"></div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button
                            type="button"
                            @click="resumeIncompleteUpload(incomplete)"
                            class="px-3 py-1 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition-colors"
                        >
                            Resume
                        </button>
                        <button
                            type="button"
                            @click="deleteIncompleteUpload(incomplete.uploadUrl)"
                            class="px-3 py-1 text-sm bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Upload Dropzone -->
    <div class="font-semibold text-sm">Assay Files</div>
    <div class="rounded-md border bg-gray-100 p-5 mt-1">
        <div
            id="drag-drop-area"
            x-ref="dropzone"
            class="bg-gray-100 border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-gray-600 transition-colors"
            @dragover.prevent="dragOver = true"
            @dragleave.prevent="dragOver = false"
            @drop.prevent="handleDrop($event)"
            @click="$refs.fileInput.click()"
            :class="{ 'border-blue-500 bg-blue-50': dragOver }"
        >
            <div class="flex flex-col items-center gap-2">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-gray-600 font-medium">Drag & drop files here or click to browse</p>
                <p class="text-sm text-gray-500">Files will be uploaded using TUS protocol</p>
            </div>
            <input
                type="file"
                x-ref="fileInput"
                @change="handleFileSelect($event)"
                multiple
                class="hidden"
            >
        </div>
    </div>

    <!-- Upload Progress & Controls -->
    <div class='p-4 mt-5 border border-gray-300 rounded-md bg-white' x-show="uploads.length > 0">
        <h3 class="font-semibold mb-3 text-gray-600">Upload Progress</h3>
        <template x-for="upload in uploads" :key="upload.id">
            <div class="mb-4 p-3 border border-gray-200 rounded">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-medium text-sm text-gray-600" x-text="upload.filename"></span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded"
                              :class="{
                                  'bg-blue-100 text-blue-700': upload.status === 'uploading',
                                  'bg-green-100 text-green-700': upload.status === 'completed',
                                  'bg-red-100 text-red-700': upload.status === 'error',
                                  'bg-yellow-100 text-yellow-700': upload.status === 'paused',
                                  'bg-gray-100 text-gray-700': upload.status === 'pending' || upload.status === 'cancelled'
                              }"
                              x-text="upload.status"></span>

                        <!-- Control Buttons -->
                        <div class="flex gap-1">
                            <!-- Pause Button -->
                            <button
                                type="button"
                                @click="pauseUpload(upload.id)"
                                x-show="upload.status === 'uploading'"
                                class="text-xs px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors"
                                title="Pause upload"
                            >
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                                </svg>
                            </button>

                            <!-- Resume Button -->
                            <button
                                type="button"
                                @click="resumeUpload(upload.id)"
                                x-show="upload.status === 'paused'"
                                class="text-xs px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition-colors"
                                title="Resume upload"
                            >
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </button>

                            <!-- Cancel Button -->
                            <button
                                type="button"
                                @click="cancelUpload(upload.id)"
                                x-show="upload.status === 'uploading' || upload.status === 'paused' || upload.status === 'pending'"
                                class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors"
                                title="Cancel upload"
                            >
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                    <div class="bg-blue-600 h-2 rounded-full transition-all"
                         :class="{
                             'bg-blue-600': upload.status === 'uploading',
                             'bg-green-600': upload.status === 'completed',
                             'bg-yellow-600': upload.status === 'paused',
                             'bg-red-600': upload.status === 'error',
                             'bg-gray-600': upload.status === 'cancelled'
                         }"
                         :style="`width: ${upload.progress}%`"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span x-text="`${upload.progress}%`"></span>
                    <span x-text="`${formatBytes(upload.bytesUploaded)} / ${formatBytes(upload.bytesTotal)}`"></span>
                </div>
                <div class="mt-2 text-xs text-gray-600" x-show="upload.url" x-text="`URL: ${upload.url}`"></div>
            </div>
        </template>
    </div>

    <!-- File Access and Management -->
    <div class="mt-5 border border-gray-500 p-3" x-data="{ uploadedFiles: $wire.entangle('infos') }">
        <h3 class="font-semibold mb-2 text-gray-700">Uploaded Files</h3>
        <template x-if="uploadedFiles && uploadedFiles.length > 0">
            <ul>
                <template x-for="(info, index) in uploadedFiles" :key="info?.ID || index">
                    <li class='mt-1' x-show="info && Object.keys(info).length > 0">
                        <span class='text-emerald-500 font-semibold cursor-pointer'
                              @click="$wire.call('download', info.Storage?.Key, info.MetaData?.filename)"
                              x-text="info.MetaData?.filename || 'Unknown'"></span>
                        <span x-text="'[' + (info.MetaData?.filetype || 'unknown') + ']'"></span>
                        <span class="bg-red-300 border border-red-800 text-red-900 text-sm font-semibold py-0 px-2 rounded-md cursor-pointer"
                              @click="$wire.call('delete', info.Storage?.Key)">DELETE</span>
                    </li>
                </template>
            </ul>
        </template>
        <template x-if="!uploadedFiles || uploadedFiles.length === 0">
            <p class="text-sm text-gray-500 italic">No files uploaded yet.</p>
        </template>
    </div>
</div>

@script
<script>
    Alpine.data('tusUploader', () => ({
        dragOver: false,
        uploads: [],
        uploadInstances: {},
        incompleteUploads: [],
        tusEndpoint: '{{ env("TUS_URL") }}',
        tusReady: false,

        init() {
            console.log('🔧 TUS Uploader initialized');
            this.waitForTus().then(() => {
                console.log('✓ TUS library loaded');
                this.findIncompleteUploads();
            }).catch(err => {
                console.error('✗ Failed to load TUS library:', err);
                this.findIncompleteUploads();
            });
        },

        async waitForTus(maxAttempts = 20, interval = 100) {
            for (let i = 0; i < maxAttempts; i++) {
                if (typeof tus !== 'undefined') {
                    this.tusReady = true;
                    return true;
                }
                await new Promise(resolve => setTimeout(resolve, interval));
            }
            throw new Error('TUS library not loaded after ' + (maxAttempts * interval) + 'ms');
        },

        findIncompleteUploads() {
            console.log('🔍 Scanning for incomplete uploads...');
            this.incompleteUploads = [];
            const tusEntries = {};

            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                const value = localStorage.getItem(key);

                if (key && key.startsWith('tus::')) {
                    const parts = key.split('::');

                    if (parts.length === 3) {
                        let fingerprint = parts[1];
                        let type = parts[2];

                        if (!tusEntries[fingerprint]) {
                            tusEntries[fingerprint] = {};
                        }

                        try {
                            const parsedValue = JSON.parse(value);

                            if (parsedValue.uploadUrl) {
                                tusEntries[fingerprint].url = parsedValue.uploadUrl;
                                tusEntries[fingerprint].size = parsedValue.size;
                                tusEntries[fingerprint].metadata = parsedValue.metadata;
                                tusEntries[fingerprint].creationTime = parsedValue.creationTime;
                            } else {
                                tusEntries[fingerprint][type] = parsedValue;
                            }
                        } catch (e) {
                            tusEntries[fingerprint][type] = value;
                        }
                    }
                }
            }

            console.log('✓ Found', Object.keys(tusEntries).length, 'TUS fingerprint(s)');

            for (const fingerprint of Object.keys(tusEntries)) {
                const entry = tusEntries[fingerprint];
                const uploadUrl = entry.url;

                if (uploadUrl) {
                    const offset = parseInt(entry.upload_offset || entry.offset) || 0;
                    let metadata = {};

                    if (entry.metadata && typeof entry.metadata === 'object') {
                        metadata = entry.metadata;
                    }

                    const size = parseInt(entry.size) || 0;

                    this.incompleteUploads.push({
                        uploadUrl: uploadUrl,
                        fingerprint: fingerprint,
                        offset: offset,
                        size: size,
                        metadata: metadata
                    });

                    this.checkUploadStatus(uploadUrl, fingerprint, offset, metadata).catch(err => {
                        console.error('checkUploadStatus error:', err);
                    });
                }
            }

            console.log('✓ Scan complete -', this.incompleteUploads.length, 'incomplete upload(s) found');
        },

        async checkUploadStatus(uploadUrl, fingerprint, offset, metadata) {
            const existingIndex = this.incompleteUploads.findIndex(u => u.fingerprint === fingerprint);

            try {
                const response = await fetch(uploadUrl, { method: 'HEAD' });

                if (response.ok) {
                    const uploadOffset = parseInt(response.headers.get('upload-offset')) || 0;
                    const uploadLength = parseInt(response.headers.get('upload-length')) || 0;

                    if (existingIndex !== -1) {
                        if (uploadLength > 0 && uploadOffset < uploadLength) {
                            this.incompleteUploads[existingIndex].offset = uploadOffset;
                            this.incompleteUploads[existingIndex].size = uploadLength;
                            console.log('✓ Verified incomplete:', metadata.filename, `(${uploadOffset}/${uploadLength} bytes)`);
                        } else if (uploadOffset >= uploadLength && uploadLength > 0) {
                            console.log('✓ Upload complete, removing:', metadata.filename);
                            this.incompleteUploads.splice(existingIndex, 1);
                            this.removeIncompleteUpload(uploadUrl);
                        }
                    }
                } else {
                    if (response.status === 404 && existingIndex !== -1) {
                        console.log('Upload not found on server (404), removing:', metadata.filename);
                        this.incompleteUploads.splice(existingIndex, 1);
                        this.removeIncompleteUpload(uploadUrl);
                    }
                }
            } catch (error) {
                console.warn('Error checking upload status:', error.message);
            }
        },

        resumeIncompleteUpload(incomplete) {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '*/*';

            fileInput.onchange = (e) => {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size !== incomplete.size) {
                    alert(`File size mismatch!\nExpected: ${this.formatBytes(incomplete.size)}\nSelected: ${this.formatBytes(file.size)}\n\nPlease select the correct file.`);
                    return;
                }

                if (file.name !== incomplete.metadata.filename) {
                    const proceed = confirm(`File name mismatch!\nExpected: ${incomplete.metadata.filename}\nSelected: ${file.name}\n\nThe file size matches. Continue anyway?`);
                    if (!proceed) return;
                }

                const uploadId = Date.now() + Math.random();

                this.uploads.push({
                    id: uploadId,
                    filename: file.name,
                    status: 'resuming',
                    progress: Math.round((incomplete.offset / incomplete.size) * 100),
                    bytesUploaded: incomplete.offset,
                    bytesTotal: file.size,
                    url: null
                });

                const additionalMetadata = JSON.stringify(this.$wire.get('data'));
                const upload = this.tusupload(file, uploadId, additionalMetadata, incomplete.uploadUrl);
                this.uploadInstances[uploadId] = upload;
                upload.start();
            };

            fileInput.click();
        },

        deleteIncompleteUpload(uploadUrl) {
            this.$wire.call('deleteIncompleteUpload', uploadUrl);
            this.removeIncompleteUpload(uploadUrl);
        },

        removeIncompleteUpload(uploadUrl) {
            this.incompleteUploads = this.incompleteUploads.filter(u => u.uploadUrl !== uploadUrl);
            const keysToRemove = [];

            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (!key) continue;

                const value = localStorage.getItem(key);

                if (key.startsWith('tus::')) {
                    let shouldRemove = false;

                    try {
                        const parsed = JSON.parse(value);
                        if (parsed && parsed.uploadUrl === uploadUrl) {
                            shouldRemove = true;
                        }
                    } catch (e) {}

                    if (shouldRemove) {
                        const parts = key.split('::');
                        if (parts.length >= 2) {
                            const fingerprint = parts[1];
                            keysToRemove.push(`tus::${fingerprint}::upload_url`);
                            keysToRemove.push(`tus::${fingerprint}::upload_offset`);
                            keysToRemove.push(`tus::${fingerprint}::upload_metadata`);
                            keysToRemove.push(key);
                        }
                    }
                }
            }

            keysToRemove.forEach(key => localStorage.removeItem(key));
        },

        clearIncompleteUploads() {
            if (confirm('Are you sure you want to clear all incomplete uploads? This cannot be undone.')) {
                this.incompleteUploads.forEach(incomplete => {
                    this.deleteIncompleteUpload(incomplete.uploadUrl);
                });
                this.incompleteUploads = [];
            }
        },

        handleDrop(event) {
            this.dragOver = false;
            const files = Array.from(event.dataTransfer.files);
            this.uploadFiles(files);
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            this.uploadFiles(files);
            event.target.value = '';
        },

        uploadFiles(files) {
            files.forEach(file => {
                const uploadId = Date.now() + Math.random();

                this.uploads.push({
                    id: uploadId,
                    filename: file.name,
                    status: 'pending',
                    progress: 0,
                    bytesUploaded: 0,
                    bytesTotal: file.size,
                    url: null
                });

                this.startTusUpload(file, uploadId);
            });
        },

        startTusUpload(file, uploadId) {
            if (!this.tusReady) {
                console.error('TUS library not ready');
                const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);
                if (uploadIndex !== -1) {
                    this.uploads[uploadIndex].status = 'error';
                }
                return;
            }

            const additionalMetadata = JSON.stringify(this.$wire.get('data'));
            const upload = this.tusupload(file, uploadId, additionalMetadata);
            this.uploadInstances[uploadId] = upload;
            upload.start();
        },

        tusupload(file, uploadId, additionalMetadata, upload_url = null) {
            const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);
            return new tus.Upload(file, {
                endpoint: this.tusEndpoint,
                uploadUrl: upload_url,
                retryDelays: [0, 3000, 5000, 10000, 20000],
                metadata: {
                    filename: file.name,
                    filetype: file.type,
                    formdata: additionalMetadata
                },
                onError: (error) => {
                    console.error('TUS upload error:', error);
                    if (uploadIndex !== -1) {
                        this.uploads[uploadIndex].status = 'error';
                    }

                    if (error.message && error.message.includes('CORS')) {
                        alert('CORS Error: The TUS server at ' + this.tusEndpoint + ' is blocking cross-origin requests. Please configure CORS headers on the server.');
                    }

                    this.$wire.call('uploadError', {
                        filename: file.name,
                        error: error.message
                    });
                },
                onProgress: (bytesUploaded, bytesTotal) => {
                    if (uploadIndex !== -1) {
                        const percentage = Math.round((bytesUploaded / bytesTotal) * 100);
                        this.uploads[uploadIndex].progress = percentage;
                        this.uploads[uploadIndex].bytesUploaded = bytesUploaded;
                        this.uploads[uploadIndex].bytesTotal = bytesTotal;
                        this.uploads[uploadIndex].status = 'uploading';
                    }
                },
                onSuccess: () => {
                    console.log('✓ Upload completed:', upload_url);
                    if (uploadIndex !== -1) {
                        this.uploads[uploadIndex].status = 'completed';
                        this.uploads[uploadIndex].progress = 100;
                        this.uploads[uploadIndex].url = upload_url;
                    }

                    this.removeIncompleteUpload(upload_url);
                    this.$wire.call('uploadComplete', {
                        filename: file.name,
                        filetype: file.type,
                        url: upload_url,
                        metadata: {
                            formdata: additionalMetadata
                        }
                    });
                }
            });
        },

        pauseUpload(uploadId) {
            const upload = this.uploadInstances[uploadId];
            const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);

            if (upload && uploadIndex !== -1) {
                upload.abort();
                this.uploads[uploadIndex].status = 'paused';
            }
        },

        resumeUpload(uploadId) {
            const upload = this.uploadInstances[uploadId];
            const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);

            if (upload && uploadIndex !== -1) {
                this.uploads[uploadIndex].status = 'uploading';
                upload.start();
            }
        },

        cancelUpload(uploadId) {
            const upload = this.uploadInstances[uploadId];
            const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);
            if (upload && uploadIndex !== -1) {
                upload.abort(true);
                this.uploads[uploadIndex].status = 'cancelled';
                delete this.uploadInstances[uploadId];
            }
        },

        formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
    }));
</script>
@endscript
