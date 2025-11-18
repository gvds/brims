<x-filament-panels::page>

    <div class="bg-emerald-700 max-w-max px-5 py-1">TUS_URL: {{env('TUS_URL')}}</div>

    <div x-cloak x-data="tusUploader()">

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

        <form id="tustest-form" x-ref="form" wire:submit='' class='my-2'>
            {{-- @csrf --}}
            <div>Additional Metadata</div>
            <div class="text-gray-800"><input type="text" name="Additional" class='bg-white border border-gray-300 rounded-md p-2' wire:model='txt'></div>

        </form>

        <!-- Upload Dropzone -->
        <div class="rounded-md w-1/3 border bg-gray-100 p-5 mt-5">
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

        <div class="mt-5 border border-gray-500 p-3">
            <ul>
                @foreach ($files as $file)
                <li>
                    {{$file}}
                </li>
                @endforeach
            </ul>
        </div>
@dump($infos)
        <div class="mt-5 border border-gray-500 p-3">
            <ul>
                @foreach ($infos as $info)
                <li class='mt-1' wire:key='"{{ $info['ID'] }}'>
                    @continue(empty($info))
                    <span class='text-emerald-500 font-semibold cursor-pointer'
                    wire:click="download('{{$info['Storage']['Key']}}', '{{$info['MetaData']['filename']}}')">{{$info['MetaData']['filename']}}</span>
                    [{{$info['MetaData']['filetype']}}] <span
                    class="bg-red-300 border border-red-800 text-red-900 text-sm font-semibold py-0 px-2 rounded-md cursor-pointer"
                    wire:click="delete('{{$info['Storage']['Key']}}')">DELETE</span>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-5 border border-gray-500 p-3">
            TUS Result:
            <div>{{ $filename }}</div>
            <div>{{ $filetype }}</div>
            <div>{{ $savedfilename }}</div>
        </div>
    </div>

    @script
    <script>
        Alpine.data('tusUploader', () => ({
            dragOver: false,
            uploads: [],
            uploadInstances: {}, // Store TUS upload instances by uploadId
            incompleteUploads: [], // Store incomplete uploads from localStorage
            tusEndpoint: '{{ env("TUS_URL") }}',
            tusReady: false,

            init() {
                console.log('🔧 TUS Uploader initialized');

                // Wait for TUS to be available
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

                // Clear existing incomplete uploads before scanning
                this.incompleteUploads = [];

                // Collect all TUS entries
                const tusEntries = {};

                // First pass: collect all TUS-related entries
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    const value = localStorage.getItem(key);

                    // Check if this is a TUS entry (tus:: prefix)
                    if (key && key.startsWith('tus::')) {
                        // TUS library format: tus::fingerprint::type
                        const parts = key.split('::');

                        if (parts.length >= 2) {
                            let fingerprint, type;

                            if (parts.length === 2) {
                                fingerprint = parts[1];
                                type = 'url';
                            } else if (parts.length === 3) {
                                fingerprint = parts[1];
                                type = parts[2];
                            } else {
                                fingerprint = parts.slice(1).join('::');
                                type = 'unknown';
                            }

                            if (!tusEntries[fingerprint]) {
                                tusEntries[fingerprint] = {};
                            }

                            // Parse the value if it's JSON
                            try {
                                const parsedValue = JSON.parse(value);

                                // Store parsed fields directly on the entry
                                if (parsedValue.uploadUrl) {
                                    tusEntries[fingerprint].url = parsedValue.uploadUrl;
                                    tusEntries[fingerprint].size = parsedValue.size;
                                    tusEntries[fingerprint].metadata = parsedValue.metadata;
                                    tusEntries[fingerprint].creationTime = parsedValue.creationTime;
                                } else {
                                    // Fallback: store as-is with type as key
                                    tusEntries[fingerprint][type] = parsedValue;
                                }
                            } catch (e) {
                                // Not JSON, store raw value
                                tusEntries[fingerprint][type] = value;
                            }
                        }
                    }
                }

                console.log('✓ Found', Object.keys(tusEntries).length, 'TUS fingerprint(s)');

                // Process each fingerprint
                for (const fingerprint of Object.keys(tusEntries)) {
                    const entry = tusEntries[fingerprint];

                    // Get upload URL - could be stored as 'url' or 'upload_url'
                    const uploadUrl = entry.url || entry.upload_url;

                    if (uploadUrl) {
                        const offset = parseInt(entry.upload_offset || entry.offset) || 0;
                        let metadata = {};

                        // Metadata might already be an object or a JSON string
                        if (entry.metadata) {
                            if (typeof entry.metadata === 'object') {
                                metadata = entry.metadata;
                            } else {
                                try {
                                    metadata = JSON.parse(entry.metadata);
                                } catch (e) {
                                    console.warn('Failed to parse metadata:', e);
                                }
                            }
                        } else if (entry.upload_metadata) {
                            try {
                                metadata = JSON.parse(entry.upload_metadata);
                            } catch (e) {
                                console.warn('Failed to parse upload_metadata:', e);
                            }
                        }

                        // Get size
                        const size = parseInt(entry.size) || 0;

                        // Add to incomplete list (will be verified by HEAD request)
                        this.incompleteUploads.push({
                            uploadUrl: uploadUrl,
                            fingerprint: fingerprint,
                            offset: offset,
                            size: size,
                            metadata: metadata
                        });

                        // Check upload status on server (async)
                        this.checkUploadStatus(uploadUrl, fingerprint, offset, metadata).catch(err => {
                            console.error('checkUploadStatus error:', err);
                        });
                    }
                }

                console.log('✓ Scan complete -', this.incompleteUploads.length, 'incomplete upload(s) found');
            },

            async checkUploadStatus(uploadUrl, fingerprint, offset, metadata) {
                // Find the existing entry in incompleteUploads
                const existingIndex = this.incompleteUploads.findIndex(u => u.fingerprint === fingerprint);

                console.log(this.incompleteUploads)
                try {
                    const response = await fetch(uploadUrl, {
                        method: 'HEAD',
                    });

                    if (response.ok) {
                        const uploadOffset = parseInt(response.headers.get('Upload-Offset')) || 0;
                        const uploadLength = parseInt(response.headers.get('Upload-Length')) || 0;

                        // Update existing entry with server data
                        if (existingIndex !== -1) {
                            if (uploadLength > 0 && uploadOffset < uploadLength) {
                                // Update with real server data
                                this.incompleteUploads[existingIndex].offset = uploadOffset;
                                this.incompleteUploads[existingIndex].size = uploadLength;
                                console.log('✓ Verified incomplete:', metadata.filename, `(${uploadOffset}/${uploadLength} bytes)`);
                            } else if (uploadOffset >= uploadLength && uploadLength > 0) {
                                // Upload is complete, remove from list and clean up localStorage
                                console.log('✓ Upload complete, removing:', metadata.filename);
                                this.incompleteUploads.splice(existingIndex, 1);
                                this.removeIncompleteUpload(uploadUrl);
                            }
                        }
                    } else {
                        // If 404, remove from list and clean up localStorage
                        if (response.status === 404 && existingIndex !== -1) {
                            console.log('Upload not found on server (404), removing:', metadata.filename);
                            this.incompleteUploads.splice(existingIndex, 1);
                            this.removeIncompleteUpload(uploadUrl);
                        }
                    }
                } catch (error) {
                    console.warn('Error checking upload status:', error.message);
                    // Keep in list so user can try to resume or remove
                }
            },

            resumeIncompleteUpload(incomplete) {
                console.log('Resuming upload:', incomplete);

                // Create a file input to let user select the file again
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = '*/*';

                fileInput.onchange = (e) => {
                    const file = e.target.files[0];
                    if (!file) {
                        console.log('No file selected');
                        return;
                    }

                    // Verify file matches the incomplete upload
                    if (file.size !== incomplete.size) {
                        alert(`File size mismatch!\nExpected: ${this.formatBytes(incomplete.size)}\nSelected: ${this.formatBytes(file.size)}\n\nPlease select the correct file.`);
                        return;
                    }

                    if (file.name !== incomplete.metadata.filename) {
                        const proceed = confirm(`File name mismatch!\nExpected: ${incomplete.metadata.filename}\nSelected: ${file.name}\n\nThe file size matches. Continue anyway?`);
                        if (!proceed) {
                            return;
                        }
                    }

                    const uploadId = Date.now() + Math.random();

                    // Add to active uploads
                    this.uploads.push({
                        id: uploadId,
                        filename: file.name,
                        status: 'resuming',
                        progress: Math.round((incomplete.offset / incomplete.size) * 100),
                        bytesUploaded: incomplete.offset,
                        bytesTotal: file.size,
                        url: null
                    });

                    // Get additional metadata from form
                    const additionalMetadata = this.$refs.form?.querySelector('[name="Additional"]')?.value || '';

                    // Create TUS upload instance with the file
                    const upload = new tus.Upload(file, {
                        endpoint: this.tusEndpoint,
                        uploadUrl: incomplete.uploadUrl,
                        retryDelays: [0, 3000, 5000, 10000, 20000],
                        metadata: {
                            filename: file.name,
                            filetype: file.type,
                            additional: additionalMetadata
                        },
                        onError: (error) => {
                            console.error('TUS resume error:', error);
                            const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);
                            if (uploadIndex !== -1) {
                                this.uploads[uploadIndex].status = 'error';
                            }
                            this.$wire.call('uploadError', {
                                filename: file.name,
                                error: error.message
                            });
                        },
                        onProgress: (bytesUploaded, bytesTotal) => {
                            const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);
                            if (uploadIndex !== -1) {
                                const percentage = Math.round((bytesUploaded / bytesTotal) * 100);
                                this.uploads[uploadIndex].progress = percentage;
                                this.uploads[uploadIndex].bytesUploaded = bytesUploaded;
                                this.uploads[uploadIndex].bytesTotal = bytesTotal;
                                this.uploads[uploadIndex].status = 'uploading';
                            }
                        },
                        onSuccess: () => {
                            console.log('✓ Upload resumed and completed:', incomplete.uploadUrl);
                            const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);
                            if (uploadIndex !== -1) {
                                this.uploads[uploadIndex].status = 'completed';
                                this.uploads[uploadIndex].progress = 100;
                                this.uploads[uploadIndex].url = incomplete.uploadUrl;
                            }

                            // Remove from incomplete list
                            this.removeIncompleteUpload(incomplete.uploadUrl);

                            // Notify Livewire component
                            this.$wire.call('uploadComplete', {
                                filename: file.name,
                                filetype: file.type,
                                url: incomplete.uploadUrl,
                                metadata: {
                                    additional: additionalMetadata
                                }
                            });
                        }
                    });

                    // Store the upload instance
                    this.uploadInstances[uploadId] = upload;

                    // Start resuming the upload
                    console.log('Starting resume from offset:', incomplete.offset);
                    upload.start();
                };

                // Trigger file selection
                fileInput.click();
            },

            deleteIncompleteUpload(uploadUrl) {
                // Call Livewire method to delete from TUS server and storage
                this.$wire.call('deleteIncompleteUpload', uploadUrl);

                this.removeIncompleteUpload(uploadUrl);
            },

            removeIncompleteUpload(uploadUrl) {
                console.log('Removing incomplete upload:', uploadUrl);

                // Remove from incomplete uploads list
                this.incompleteUploads = this.incompleteUploads.filter(u => u.uploadUrl !== uploadUrl);

                // Clean up localStorage entries - need to handle both formats
                const keysToRemove = [];

                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (!key) continue;

                    const value = localStorage.getItem(key);

                    // Format 1: tus:: prefixed keys
                    if (key.startsWith('tus::')) {
                        // Check if the value matches the uploadUrl or if it's JSON containing the uploadUrl
                        let shouldRemove = false;

                        if (value === uploadUrl) {
                            shouldRemove = true;
                        } else {
                            try {
                                const parsed = JSON.parse(value);
                                if (parsed && parsed.uploadUrl === uploadUrl) {
                                    shouldRemove = true;
                                }
                            } catch (e) {
                                // Not JSON, skip
                            }
                        }

                        if (shouldRemove) {
                            const parts = key.split('::');
                            if (parts.length >= 2) {
                                const fingerprint = parts[1];
                                // Remove all related keys for this fingerprint
                                keysToRemove.push(`tus::${fingerprint}::upload_url`);
                                keysToRemove.push(`tus::${fingerprint}::upload_offset`);
                                keysToRemove.push(`tus::${fingerprint}::upload_metadata`);
                                keysToRemove.push(key); // Also remove the current key
                            }
                        }
                    }
                }

                // Remove all identified keys
                keysToRemove.forEach(key => {
                    console.log('Removing localStorage key:', key);
                    localStorage.removeItem(key);
                });

                console.log('✓ Removed', keysToRemove.length, 'localStorage key(s)');
            },

            clearIncompleteUploads() {
                if (confirm('Are you sure you want to clear all incomplete uploads? This cannot be undone.')) {
                    // Delete all from server and remove from localStorage
                    this.incompleteUploads.forEach(incomplete => {
                        this.deleteIncompleteUpload(incomplete.uploadUrl);
                    });

                    // Clear the list
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
                event.target.value = ''; // Reset input
            },

            uploadFiles(files) {
                files.forEach(file => {
                    const uploadId = Date.now() + Math.random();

                    // Add upload to tracking array
                    this.uploads.push({
                        id: uploadId,
                        filename: file.name,
                        status: 'pending',
                        progress: 0,
                        bytesUploaded: 0,
                        bytesTotal: file.size,
                        url: null
                    });

                    // Start TUS upload
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

                const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);

                // Get additional metadata from form
                const additionalMetadata = this.$refs.form?.querySelector('[name="Additional"]')?.value || '';

                const upload = new tus.Upload(file, {
                    endpoint: this.tusEndpoint,
                    retryDelays: [0, 3000, 5000, 10000, 20000],
                    // uploadDataDuringCreation: true,
                    chunkSize: 33554432, // 32MB
                    // parallelUploads: Math.ceil(file.size / 33554432),
                    metadata: {
                        filename: file.name,
                        filetype: file.type,
                        additional: additionalMetadata,
                    },
                    onError: (error) => {
                        console.error('TUS upload error:', error);
                        if (uploadIndex !== -1) {
                            this.uploads[uploadIndex].status = 'error';
                        }

                        // Check if CORS error
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
                        console.log('TUS upload completed:', upload.url);
                        if (uploadIndex !== -1) {
                            this.uploads[uploadIndex].status = 'completed';
                            this.uploads[uploadIndex].progress = 100;
                            this.uploads[uploadIndex].url = upload.url;
                            this.removeIncompleteUpload(upload.url);
                            }

                        // Notify Livewire component
                        this.$wire.call('uploadComplete', {
                            filename: file.name,
                            filetype: file.type,
                            url: upload.url,
                            metadata: {
                                additional: additionalMetadata
                            }
                        });
                    }
                });

                // Store the upload instance for pause/resume
                this.uploadInstances[uploadId] = upload;

                // Start the upload
                upload.start();
            },

            pauseUpload(uploadId) {
                const upload = this.uploadInstances[uploadId];
                const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);

                if (upload && uploadIndex !== -1) {
                    upload.abort();
                    this.uploads[uploadIndex].status = 'paused';
                    console.log('Upload paused:', uploadId);
                }
            },

            resumeUpload(uploadId) {
                const upload = this.uploadInstances[uploadId];
                const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);

                if (upload && uploadIndex !== -1) {
                    this.uploads[uploadIndex].status = 'uploading';
                    upload.start();
                    console.log('Upload resumed:', uploadId);
                }
            },

            cancelUpload(uploadId) {
                const upload = this.uploadInstances[uploadId];
                const uploadIndex = this.uploads.findIndex(u => u.id === uploadId);

                if (upload && uploadIndex !== -1) {
                    upload.abort(true); // true = delete the upload from server
                    this.uploads[uploadIndex].status = 'cancelled';
                    delete this.uploadInstances[uploadId];
                    console.log('Upload cancelled:', uploadId);
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

</x-filament-panels::page>
