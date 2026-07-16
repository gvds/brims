<?php

use App\Filament\App\Pages\Calendar;
use App\Models\User;
use Filament\Tables\Table;

it('uses a progressively increasing content grid across breakpoints', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $page = new Calendar;
    $table = Calendar::table(new Table($page));

    expect($table->getContentGrid())->toBe([
        'sm' => 2,
        'lg' => 3,
        'xl' => 4,
        '2xl' => 5,
    ]);
});
