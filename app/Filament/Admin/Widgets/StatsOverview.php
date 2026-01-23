<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Project;
use App\Models\Study;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Teams', Cache::flexible('teams', [20, 120], fn() => Team::count())),
            Stat::make('Active Users', Cache::flexible('active_users', [20, 120], fn() => User::where('active', true)->count())),
            Stat::make('Projects', Cache::flexible('projects', [20, 120], fn() => Project::count())),
            Stat::make('Studies', Cache::flexible('studies', [20, 120], fn() => Study::count())),
            // Stat::make('Projects', Project::count()),
            // Stat::make('Projects', Project::count()),
        ];
    }

    protected function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 5,
            '2xl' => 8
        ];
    }
}
