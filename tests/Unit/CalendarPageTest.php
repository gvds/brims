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
        'xl' => 4,
        '2xl' => 5,
        '3xl' => 6
    ]);
});
