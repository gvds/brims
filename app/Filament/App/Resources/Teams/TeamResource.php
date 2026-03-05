<?php

namespace App\Filament\Resources\Teams;

use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ViewTeam;
use App\Filament\Resources\Teams\Schemas\TeamForm;
use App\Filament\Resources\Teams\Schemas\TeamInfolist;
use App\Models\Team;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static bool $shouldRegisterNavigation = false;

    #[\Override]
    public static function getIndexUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        return static::getUrl('view', ['record' => Auth::user()->team_id]);
    }

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return TeamForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return TeamInfolist::configure($schema);
    }

    // #[\Override]
    // public static function table(Table $table): Table
    // {
    //     return TeamsTable::configure($table);
    // }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            RelationManagers\MembersRelationManager::class,
            RelationManagers\ProjectsRelationManager::class,
            RelationManagers\ProtocolsRelationManager::class,
            RelationManagers\AssayDefinitionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            // 'index' => ListTeams::route('/'),
            // 'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
            'view' => ViewTeam::route('/{record}'),
        ];
    }
}
