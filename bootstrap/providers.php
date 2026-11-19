<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\AppPanelProvider;
use App\Providers\Filament\ProjectPanelProvider;
use App\Providers\Filament\StudyPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    AppPanelProvider::class,
    ProjectPanelProvider::class,
    StudyPanelProvider::class,
];
