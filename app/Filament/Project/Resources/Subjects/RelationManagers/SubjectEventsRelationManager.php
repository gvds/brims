<?php

namespace App\Filament\Project\Resources\Subjects\RelationManagers;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Models\Subject;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubjectEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjectEvents';

    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             Select::make('subject_id')
    //                 ->relationship('subject', 'id')
    //                 ->required(),
    //             Select::make('event_id')
    //                 ->relationship('event', 'name')
    //                 ->required(),
    //             TextInput::make('iteration')
    //                 ->required()
    //                 ->numeric()
    //                 ->default(1),
    //             Select::make('status')
    //                 ->options(EventStatus::class)
    //                 ->required()
    //                 ->default(0),
    //             Select::make('labelstatus')
    //                 ->options(LabelStatus::class)
    //                 ->required()
    //                 ->default(0),
    //             DatePicker::make('eventDate'),
    //             DatePicker::make('minDate'),
    //             DatePicker::make('maxDate'),
    //             DatePicker::make('logDate'),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('event.name')
            // ->modifyQueryUsing(fn(Builder $query) => $query->join('arms', 'events.arm_id', '=', 'arms.id'))
            // ->modifyQueryUsing(fn(Builder $query) => dd($query->get()))
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('event.arm.name'),
                TextColumn::make('event.name'),
                TextColumn::make('status'),
                TextColumn::make('eventDate')
                    ->date(),
                TextColumn::make('minDate')
                    ->date(),
                TextColumn::make('maxDate')
                    ->date(),
                TextColumn::make('logDate')
                    ->date(),
                TextColumn::make('event.event_order')
                    ->label('Event Order'),
                IconColumn::make('event.repeatable')
                    ->boolean()
                    ->label('Repeatable'),
                TextColumn::make('iteration')
                    ->numeric(),
                TextColumn::make('labelstatus'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultKeySort(false)
            // ->defaultSort(
            //     fn(Builder $query) => $query
            //     ->orderBy('arm_num', 'asc')
            // ->orderBy('event.event_order', 'asc')
            // ->orderBy('iteration', 'asc')
            // )
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                Action::make('newItteration')
                    ->label('New Iteration')
                    ->schema(
                        [
                            DatePicker::make('eventDate')
                                ->default(today()),
                        ]
                    )
                    ->action(function ($record, $data) {
                        $subject = Subject::find($record->subject_id);
                        $subject->events()->attach($record->id, [
                            'iteration' => $record->iteration + 1,
                            'status' => 0,
                            'labelstatus' => 0,
                            'eventDate' => $data['eventDate'],
                            // 'minDate' => now(),
                            // 'maxDate' => now(),
                        ]);
                    })
                    ->visible(fn($record) => $record->repeatable == 0)
                    ->requiresConfirmation()
                    ->icon('heroicon-o-plus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
