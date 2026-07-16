<?php

use App\Filament\App\Pages\Calendar;
use App\Models\User;
use Filament\Tables\Table;

it('uses a progressively increasing content grid across breakpoints', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $table = Calendar::table(new Table);

    expect($table->getContentGrid())->toBe([
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 5,
    ]);
});
