<?php

namespace App\Filament\Resources\Assays\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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
                        $data['user_id'] = auth()->id();

                        return $data;
                    })
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
                    ->modalWidth('w-full md:w-4/5 lg:w-3/5 xl:w-1/2 2xl:w-2/5'),
                DeleteAction::make()
                    ->using(function (Model $record): void {
                        if (isset($record->assayfile)) {
                            Storage::disk('s3')->delete($record->assayfile);
                        }
                        $record->delete();
                    })
                    ->modalHeading(fn($record) => new HtmlString('Delete Assay<br/>' . $record->name))
                    ->modalDescription(new HtmlString("This will delete all associated data files.<br/>Are you sure you want to delete this assay?")),
            ]);
    }
}
