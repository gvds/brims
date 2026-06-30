<?php

use App\Filament\Exports\SpecimenExporter;
use Filament\Actions\Exports\Models\Export;

it('filters invalid exporter column map keys', function () {
    $export = new Export;

    $columnMap = [
        'barcode' => 'Barcode',
        'originSite.name' => 'Origin Site',
        'invalid.column' => 'Invalid',
    ];

    $exporter = new SpecimenExporter($export, $columnMap, []);

    $reflection = new ReflectionProperty($exporter, 'columnMap');
    $reflection->setAccessible(true);

    $filteredColumnMap = $reflection->getValue($exporter);

    expect($filteredColumnMap)
        ->toBeArray()
        ->not->toHaveKey('invalid.column');

    expect(array_keys($filteredColumnMap))->toEqual([
        'barcode',
        'originSite.name',
    ]);
});
