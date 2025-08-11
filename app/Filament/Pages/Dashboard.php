<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    #[\Override]
    public function getColumns(): int | array
    {
        return 3;
    }
}
