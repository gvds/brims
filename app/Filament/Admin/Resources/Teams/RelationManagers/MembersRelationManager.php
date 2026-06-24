<?php

namespace App\Filament\Admin\Resources\Teams\RelationManagers;

use App\Enums\TeamRoles;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    #[\Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->regex('/^[a-z][a-z0-9.]+$/')
                    ->validationMessages([
                        'regex' => 'The :attribute may contain only lower-case letters, periods and numbers and must start with a letter.',
                    ]),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('firstname')
                    ->required()
                    ->maxLength(50),
                TextInput::make('lastname')
                    ->required()
                    ->maxLength(50),
                Select::make('team_role')
                    ->options(fn(): array|string => $this->ownerRecord->members->count() === 0 ? TeamRoles::admin() : TeamRoles::class)
                    ->required(),
                TextInput::make('telephone')
                    ->prefix('+')
                    ->mask('99 (99) 999-9999')
                    ->maxLength(20)
                    ->default(null),
                TextInput::make('homesite')
                    ->label('Home Site')
                    ->maxLength(10)
                    ->default(null),
                Toggle::make('active')
                    ->visibleOn('edit'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('fullname')
                    ->label('Name')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->prefix('+')
                    ->searchable(),
                TextColumn::make('homesite')
                    ->label('Home Site'),
                TextColumn::make('team_role')
                    ->label('Role'),
                IconColumn::make('active')
                    ->boolean(),
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
                Filter::make('active')
                    ->query(fn($query) => $query->where('active', true))
                    ->label('Active')
                    ->toggle(),
                SelectFilter::make('homesite')
                    ->options(fn() => User::distinct('homesite')->pluck('homesite', 'homesite'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Home Site')
                    ->placeholder('All Home Sites')
                    ->default(null),
            ])
            ->deferFilters(false)
            ->headerActions([
                AssociateAction::make()
                    ->recordTitle(fn(User $record): string => $record->fullname)
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query->where('active', true))
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['firstname', 'lastname'])
                    ->schema(fn(AssociateAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role_id')
                            ->label('Role')
                            ->options(fn() => Role::where('project_id', $this->ownerRecord->id)->pluck('name', 'id'))
                            ->required(),
                        Select::make('site_id')
                            ->label('Site')
                            ->options(
                                Site::where('project_id', $this->ownerRecord->id)->pluck('name', 'id')
                            ),
                    ]),
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['team_id'] = $this->ownerRecord->id;
                        $data['password'] = bcrypt(Str::password(32));

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
