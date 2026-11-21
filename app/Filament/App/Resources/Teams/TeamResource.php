<?php

namespace App\Filament\App\Resources\Teams;

use App\Filament\App\Resources\Teams\Pages\EditTeam;
use App\Filament\App\Resources\Teams\Pages\ListTeams;
use App\Filament\App\Resources\Teams\Pages\ViewTeam;
use App\Filament\App\Resources\Teams\Schemas\TeamForm;
use App\Filament\App\Resources\Teams\Schemas\TeamInfolist;
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

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'My Team';

    public static ?int $navigationSort = 4;

    public static function getIndexUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        return static::getUrl('view', ['record' => Auth::user()->team_id]);
    }

    public static function canAccess(): bool
    {
        return Auth::user()->team_id !== null;
    }

    public static function form(Schema $schema): Schema
    {
        return TeamForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeamInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MembersRelationManager::class,
            RelationManagers\ProjectsRelationManager::class,
            RelationManagers\ProtocolsRelationManager::class,
            RelationManagers\AssayDefinitionsRelationManager::class,
            RelationManagers\ProgrammesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeams::route('/'),
            // 'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
            'view' => ViewTeam::route('/{record}'),
        ];
    }
}
