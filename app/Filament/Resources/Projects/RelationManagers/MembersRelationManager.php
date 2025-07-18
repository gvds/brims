<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\ProjectMember;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\DateTimePicker;
use Filament\Forms\Components\DateTimePicker as ComponentsDateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle as ComponentsToggle;
use Filament\Notifications\Notification;
use Filament\Textarea;
use Filament\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function isReadOnly(): bool
    {
        return false;
    }

    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('username')
    //                 ->required(),
    //             TextInput::make('firstname')
    //                 ->required(),
    //             TextInput::make('lastname')
    //                 ->required(),
    //             TextInput::make('email')
    //                 ->email()
    //                 ->required(),
    //             TextInput::make('telephone')
    //                 ->tel()
    //                 ->default(null),
    //             TextInput::make('homesite')
    //                 ->default(null),
    //             ComponentsToggle::make('active')
    //                 ->required(),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(User $record): string => "{$record->firstname} {$record->lastname}")
            ->columns([
                TextColumn::make('fullname')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->label('Team'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['firstname', 'lastname'])
                    ->schema(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role')
                            ->options([
                                'Admin' => 'Admin',
                                'Member' => 'Member',
                            ])
                            ->required(),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        Select::make('role')
                            ->options([
                                'Admin' => 'Admin',
                                'Member' => 'Member',
                            ])
                            ->required(),
                    ])
                    ->visible(fn(User $record) => $this->getOwnerRecord()->leader_id !== $record->id),
                DetachAction::make()
                    ->visible(fn(User $record) => $this->getOwnerRecord()->leader_id !== $record->id),
                // ->before(
                //     function (User $record, DetachAction $action) {
                //         if ($record->pivot->pivotParent->members->count() === 1) {
                //             Notification::make()
                //                 ->title('Empty Team')
                //                 ->body('A team cannot be empty. Please add another member before removing this one.')
                //                 ->duration(10000)
                //                 ->danger()
                //                 ->color('danger')
                //                 ->send();
                //             $action->cancel();
                //         }
                //         $project_admin_count = ProjectMember::where('project_id', $record->pivot->project_id)
                //             ->where('role', 'Admin')
                //             ->count();
                //         if ($record->pivot->role == 'Admin' && $project_admin_count === 1) {
                //             Notification::make()
                //                 ->title('Project requires at least one admin')
                //                 ->body('A project must have at least one member with administrative permissions. Please assign either a Leader or Admin role to another member before removing this one.')
                //                 ->duration(10000)
                //                 ->danger()
                //                 ->color('danger')
                //                 ->send();
                //             $action->cancel();
                //         }
                //     }
                // ),
            ])
            ->filters([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn(Model $record): bool => $record->id === $this->getOwnerRecord()->leader_id ? false : true,
            );
    }
}
