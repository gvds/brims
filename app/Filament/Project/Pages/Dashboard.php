<?php

namespace App\Filament\Project\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    #[\Override]
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            '2xl' => 2,
        ];
    }
}
