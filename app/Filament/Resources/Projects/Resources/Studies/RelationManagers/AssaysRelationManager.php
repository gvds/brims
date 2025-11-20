<?php

namespace App\Filament\Resources\Projects\Resources\Studies\RelationManagers;

use App\Filament\Resources\Assays\AssayResource;
use App\Filament\Resources\Assays\Schemas\AssayForm;
use App\Filament\Resources\Assays\Tables\AssaysTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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
        return AssayForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        // return AssaysTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('study.title')
                    ->sortable(),
                TextColumn::make('assaydefinition.name')
                    ->sortable(),
                TextColumn::make('technologyPlatform')
                    ->searchable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['study_id'] = $this->getOwnerRecord()->id;
                        $data['user_id'] = auth()->id();

                        return $data;
                    })
                    // ->modalContentFooter(view('filament.resources.assays.pages.partials.tus-uploader', [
                    //     'infos' => $this->infos,
                    // ]))
                    ->modalWidth('w-full md:w-4/5 lg:w-3/5 xl:w-1/2 2xl:w-2/5'),
                // ->slideOver(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->using(function (Model $record, array $data): Model {

                        $record->update($data);

                        return $record;
                    })
                    // ->modalContentFooter(view('filament.resources.assays.pages.partials.tus-uploader', [
                    //     'infos' => $this->infos,
                    // ]))
                    ->modalWidth('w-full md:w-4/5 lg:w-3/5 xl:w-1/2 2xl:w-2/5'),
                // EditAction::make()
                //     ->using(function (Model $record, array $data): Model {
                //         if (isset($record->assayfile) && $data['assayfile'] != $record->assayfile) {
                //             Storage::disk('assayfiles')->delete($record->assayfile);
                //         }
                //         $record->update($data);

                //         return $record;
                //     }),
                DeleteAction::make()
                    ->using(function (Model $record): void {
                        if (isset($record->assayfile)) {
                            Storage::disk('s3')->delete($record->assayfile);
                        }
                        $record->delete();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

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

    private function getFileMetadata(): void
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
