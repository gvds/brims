<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TusTest extends Page
{
    protected string $view = 'filament.pages.tus-test';

    public $files = null;

    public $infos = [];

    // public $txt = null;

    // public $project = null;

    public $savedfilename = null;

    public $filetype = null;

    public $filename = null;

    public $resultArray = [];

    public function mount(): void
    {
        $this->getFileMetadata();
    }

    public function getInfo($file)
    {
        return Http::withHeaders([
            'Accept-Encoding' => 'gzip, deflate',
        ])->withOptions([
            'decode_content' => false,
        ])->get(Storage::disk('s3')->url($file));
    }

    public function fileExists($fileName): bool
    {
        return Storage::disk('s3')->exists($fileName);
    }

    private function getFileMetadata(): void
    {
        $this->files = Storage::disk('s3')->files();
        $this->infos = [];
        foreach ($this->files as $file) {
            // Storage::disk('s3')->setVisibility($file, 'public');
            // dump(Storage::disk('s3')->getVisibility($file));
            if (Str::endsWith($file, '.info')) {
                Storage::disk('s3')->setVisibility($file, 'public');
                $this->infos[] = json_decode($this->getInfo($file)->body(), true);
            }
        }
        // dd($this->infos);
    }

    // public function processform(): void
    // {
    //     $this->getFileMetadata();
    // }

    public function download($file, $filename)
    {
        // return Storage::disk('s3')->response($file);
        return Storage::disk('s3')->download($file, $filename);
    }

    public function delete($file)
    {
        Storage::disk('s3')->delete($file);
        Storage::disk('s3')->delete($file . '.info');
        $this->getFileMetadata();
    }

    public function uploadComplete(array $data): void
    {
        Log::info('TUS Upload completed', $data);

        $this->filename = $data['filename'] ?? null;
        $this->filetype = $data['filetype'] ?? null;
        $this->savedfilename = $data['url'] ?? null;
        $this->resultArray[] = $data;

        // Refresh file metadata from storage
        $this->getFileMetadata();

        // Optional: Show success notification
        \Filament\Notifications\Notification::make()
            ->title('Upload Complete')
            ->body("File '{$this->filename}' uploaded successfully")
            ->success()
            ->send();
    }

    public function uploadError(array $data): void
    {
        Log::error('TUS Upload error', $data);

        // Optional: Show error notification
        \Filament\Notifications\Notification::make()
            ->title('Upload Failed')
            ->body("Failed to upload '{$data['filename']}': {$data['error']}")
            ->danger()
            ->send();
    }

    public function deleteIncompleteUpload(string $uploadUrl): void
    {
        Log::info('Deleting incomplete TUS upload', ['url' => $uploadUrl]);

        // Extract file key from upload URL (last part of URL path)
        $urlParts = parse_url($uploadUrl);
        $pathParts = explode('/', trim($urlParts['path'] ?? '', '/'));
        $fileKey = end($pathParts);
        $fileName = Str::of($fileKey)->explode('+')->first();

        $storageDeleted = false;
        $partDeleted = false;

        if ($fileName) {
            try {
                $storageDeleted = Storage::disk('s3')->delete($fileName);
                $partDeleted = Storage::disk('s3')->delete($fileName . '.part');
                Storage::disk('s3')->delete($fileName . '.info');

                Log::info('Files deleted from S3 storage', [
                    'file_name' => $fileName,
                    'deleted' => $storageDeleted,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to delete from S3 storage', [
                    'file_name' => $fileName,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Show appropriate notification
        if ($storageDeleted || $partDeleted) {
            \Filament\Notifications\Notification::make()
                ->title('Upload Deleted')
                ->body('Incomplete upload removed from storage')
                ->success()
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Deletion Attempt Failed')
                ->body('Upload record removed from tracking')
                ->danger()
                ->send()
                ->persistent();
        }

        // Refresh file metadata
        $this->getFileMetadata();
    }
}
