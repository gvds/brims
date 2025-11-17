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
        // $this->project = session('currentProject');
        $this->getFileMetadata();
    }

    private function getFileMetadata(): void
    {
        $this->files = Storage::disk('s3')->files();
        foreach ($this->files as $file) {
            //         // Storage::disk('s3')->delete($file);
            Storage::disk('s3')->setVisibility($file, 'public');
            if (Str::endsWith($file, '.info')) {
                $response = Http::withHeaders([
                    'Accept-Encoding' => 'gzip, deflate'
                ])->withOptions([
                    'decode_content' => false,
                ])->get(Storage::disk('s3')->url($file));
                $this->infos[] = json_decode($response->body(), true);
            }
        }
    }

    public function processform(): void
    {
        Log::notice($this->uppyResult);
        $this->getFileMetadata();
    }

    public function download($file, $filename)
    {
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
}
