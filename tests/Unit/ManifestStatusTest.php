<?php

use App\Enums\ManifestStatus;

it('provides Filament-compatible options from the ManifestStatus enum', function () {
    $options = collect(ManifestStatus::cases())
        ->mapWithKeys(fn (ManifestStatus $s) => [$s->value => $s->getLabel()])
        ->all();

    expect($options)->toBeArray()->not->toBeEmpty();

    // ensure keys and labels match the enum
    foreach (ManifestStatus::cases() as $case) {
        expect(array_key_exists($case->value, $options))->toBeTrue();
        expect($options[$case->value])->toBe($case->getLabel());
    }

    // spot-check known values
    expect($options[ManifestStatus::Open->value])->toBe(ManifestStatus::Open->getLabel());
    expect(is_int(array_key_first($options)))->toBeTrue();
});
