<?php

namespace App\Filament\Project\Resources\Studies\Resources\Assays\Tables;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class AssaysTable
{
    public static function configure(Table $table, $relationManager = null): Table
    {
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
                TextColumn::make('assayfiles')
                    ->label('Files')
                    ->badge()
                    ->getStateUsing(fn($record) => is_array($record->assayfiles) ? count($record->assayfiles) : 0)
                    ->color(fn($state) => $state > 0 ? 'success' : 'gray'),
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
                    ->mutateDataUsing(function (array $data) use ($relationManager): array {
                        if ($relationManager) {
                            $data['study_id'] = $relationManager->getOwnerRecord()->id;
                        }
                        $data['user_id'] = Auth::id();

                        return $data;
                    })
                    ->hidden(fn(): bool => $relationManager->getOwnerRecord()->locked)
                    ->modalWidth('w-full md:w-4/5 lg:w-3/5 xl:w-1/2 2xl:w-2/5'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalContentFooter(function ($record) use ($relationManager) {
                        return view('filament.resources.assays.pages.partials.tus-uploader', [
                            'assay' => $record,
                            'infos' => $relationManager->infos,
                        ]);
                    })
                    ->modalWidth('w-full md:w-4/5 lg:w-3/5 xl:w-1/2 2xl:w-2/5'),
                EditAction::make()
                    ->hidden(fn(): bool => $relationManager->getOwnerRecord()->locked)
                    ->modalWidth('w-full md:w-4/5 lg:w-3/5 xl:w-1/2 2xl:w-2/5'),
                Action::make('download_all_files')
                    ->label('Download All Files')
                    ->icon('heroicon-o-arrow-down-on-square-stack')
                    ->color(Color::Indigo)
                    ->button()
                    ->extraAttributes(['class' => 'h-7 text-xs opacity-70'])
                    ->hidden(fn(Model $record): bool => empty($record->assayfiles))
                    ->schema([
                        TextInput::make('expiration_days')
                            ->label('Link Expiration (days)')
                            ->default(1)
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(7)
                            ->required(),
                    ])
                    ->modalWidth('sm')
                    ->action(function (Model $record, array $data) {
                        if (empty($record->assayfiles)) {
                            return;
                        }

                        try {
                            $temporarySignedUrls = [];
                            foreach ($record->assayfiles as $file) {
                                $expiration = now()->addDays($data['expiration_days']);
                                $temporarySignedUrls[] = Storage::disk('s3')->temporaryUrl($file, $expiration);
                            }
                        } catch (\Throwable $e) {
                            Log::warning('S3 unavailable during download link generation', [
                                'assay' => $record->id,
                                'error' => $e->getMessage(),
                            ]);

                            Notification::make()
                                ->title('Storage Unavailable')
                                ->body('Could not generate download links. The storage service may be temporarily unavailable.')
                                ->warning()
                                ->persistent()
                                ->send();

                            return;
                        }

                        $content = "Assay File Download Links for: {$record->name}\n";
                        $content .= 'Generated: ' . now()->format('Y-m-d H:i:s') . "\n";
                        $content .= 'Links expire: ' . now()->addDays($data['expiration_days'])->format('Y-m-d H:i:s') . "\n";
                        $content .= str_repeat('-', 50) . "\n\n";
                        $content .= implode("\n\n", $temporarySignedUrls);

                        $filename = 'download_links_' . str($record->name)->slug() . '_' . now()->format('Ymd_His') . '.txt';

                        return response()->streamDownload(function () use ($content) {
                            echo $content;
                        }, $filename, [
                            'Content-Type' => 'text/plain',
                        ]);
                    }),
                DeleteAction::make()
                    ->using(function (Model $record): void {
                        try {
                            foreach ($record->assayfiles ?? [] as $file) {
                                Storage::disk('s3')->delete($file);
                                Storage::disk('s3')->delete($file . '.info');
                            }
                        } catch (\Throwable $e) {
                            Log::warning('S3 unavailable during assay file deletion', [
                                'assay' => $record->id,
                                'error' => $e->getMessage(),
                            ]);

                            Notification::make()
                                ->title('Storage Warning')
                                ->body('The assay record was deleted but some files could not be removed from storage. They may need manual cleanup.')
                                ->warning()
                                ->persistent()
                                ->send();
                        }
                        $record->delete();
                    })
                    ->hidden(fn(): bool => $relationManager->getOwnerRecord()->locked)
                    ->modalHeading(fn($record) => new HtmlString('Delete Assay<br/>' . $record->name))
                    ->modalDescription(new HtmlString('This will delete all associated data files.<br/>Are you sure you want to delete this assay?')),
            ]);
    }
}
