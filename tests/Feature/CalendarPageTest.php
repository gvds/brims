<?php

use App\Enums\SystemRoles;
use App\Filament\App\Pages\Calendar;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('renders the calendar as a seven-column week grid', function (): void {
    Filament::setCurrentPanel('app');
    Filament::bootCurrentPanel();

    $user = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
    actingAs($user);

    livewire(Calendar::class)
        ->assertOk()
        ->assertSee('grid-cols-7');
});
