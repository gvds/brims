<?php

namespace App\Filament\Resources\Projects\Resources\Studies\RelationManagers;

use App\Filament\Resources\Assays\Schemas\AssayForm;
use App\Filament\Resources\Assays\Tables\AssaysTable;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssaysRelationManager extends RelationManager
{
    protected static string $relationship = 'assays';

    // protected static ?string $relatedResource = AssayResource::class;

    public $infos = [];

    public $files = null;

    public $filename = null;

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    #[\Override]
    public function form(Schema $schema): Schema
    {
        return AssayForm::configure($schema, 1);
    }

    #[\Override]
    public function infolist(Schema $schema): Schema
    {
        return AssayForm::configure($schema, 2);
        // return AssayInfolist::configure($schema);
    }

    #[\Override]
    public function table(Table $table): Table
    {
        return AssaysTable::configure($table, $this);
    }

    #[\Override]
    public function mount(): void
    {
        parent::mount();
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

    public function getFileMetadata(): void
    {
        $this->files = Storage::disk('s3')->files();
        $this->infos = [];
        foreach ($this->files as $file) {
            if (Str::endsWith($file, '.info')) {
                Storage::disk('s3')->setVisibility($file, 'public');
                $this->infos[] = json_decode($this->getInfo($file)->body(), true);
            }
        }
    }

    public function download($file, $filename)
    {
        if (! Storage::disk('s3')->exists($file)) {
            Notification::make()
                ->title('Download Failed')
                ->body("<strong>{$filename}</strong> could not be found")
                ->danger()
                ->send();

            return;
        }

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

        $this->getFileMetadata();

        Notification::make()
            ->title('Upload Complete')
            ->body("File '{$this->filename}' uploaded successfully")
            ->success()
            ->send();
    }

    public function uploadError(array $data): void
    {
        Log::error('TUS Upload error', $data);

        Notification::make()
            ->title('Upload Failed')
            ->body("Failed to upload <strong>{$data['filename']}</strong>: {$data['error']}")
            ->danger()
            ->send();
    }

    public function deleteIncompleteUpload(string $uploadUrl): void
    {
        Log::info('Deleting incomplete TUS upload', ['url' => $uploadUrl]);

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

        if ($storageDeleted || $partDeleted) {
            Notification::make()
                ->title('Upload Deleted')
                ->body('Incomplete upload removed from storage')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Deletion Attempt Failed')
                ->body('Upload record removed from tracking')
                ->danger()
                ->send()
                ->persistent();
        }

        $this->getFileMetadata();
    }
}
