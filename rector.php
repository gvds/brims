<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        // __DIR__ . '/database',
        __DIR__ . '/tests',
    ])
    // ->withSkip([
    //     RenamePropertyRector::class
    // ])
    ->withSets([
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
    ])
    ->withPhpSets()
    ->withComposerBased(laravel: true)
    ->withTypeCoverageLevel(6)
    ->withDeadCodeLevel(6)
    ->withCodeQualityLevel(6);
