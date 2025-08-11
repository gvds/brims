<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/database',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        RenamePropertyRector::class
    ])
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
        // LaravelSetList::LARAVEL_CODE_QUALITY,
        // LaravelSetList::LARAVEL_COLLECTION,
    ])
    ->withPhpSets(php84: true)
    ->withTypeCoverageLevel(6)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
