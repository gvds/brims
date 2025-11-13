<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TusTest extends Page
{
    protected string $view = 'filament.pages.tus-test';

    public $files = null;

    public $infos = [];

    public $uppyResult = null;

    public $txt = null;

    public $project = null;

    public $savedfilename = null;

    public $filetype = null;

    public $filename = null;

    public $resultArray = [];

    public function mount(): void
    {
        $this->project = session('currentProject');
        $this->getFileMetadata();
    }

    private function getFileMetadata(): void
    {
        $this->files = Storage::disk('s3')->files();
        foreach ($this->files as $file) {
            // Storage::disk('s3')->delete($file);
            // Storage::disk('s3')->setVisibility($file, 'private');
            // dump(Storage::disk('s3')->getVisibility($file));
            if (Str::endsWith($file, '.info')) {
                $this->infos[] = json_decode(Storage::disk('s3')->get($file));
            }
        }
    }

    public function processform(): void
    {
        Log::notice($this->uppyResult);
        $this->getFileMetadata();
    }

    public function setUppyResult($result): void
    {
        $this->uppyResult = $result;
        $this->resultArray = json_decode($result, true);
        // $this->savedfilename = $resultArray[0]['name'] ?? null;
        // $this->filetype = $resultArray[0]['type'] ?? null;
        // preg_match('/(\w+)\+/', $resultArray[0]['uploadURL'], $matches);
        // $this->filename = $matches[1] ?? null;
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
