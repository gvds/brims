<?php

use App\Filament\Imports\SubjectEventImporter;
use App\Models\Project;
use App\Models\SubjectEvent;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Validator;

it('only resolves supported relationships for subject event import columns', function () {
    $importer = new SubjectEventImporter(new Import, [], []);

    $recordProperty = new ReflectionProperty(Importer::class, 'record');
    $recordProperty->setValue($importer, new SubjectEvent);

    foreach ($importer->getCachedColumns() as $column) {
        $relationship = $column->getRelationship();

        expect(
            $relationship === null ||
            $relationship instanceof BelongsTo ||
            $relationship instanceof BelongsToMany,
        )->toBeTrue();
    }
});

it('validates the event column with Laravel validation callback arguments', function () {
    $project = new Project;
    $project->id = 1;

    $importer = new SubjectEventImporter(new Import, [], ['project' => $project]);

    $recordProperty = new ReflectionProperty(Importer::class, 'record');
    $recordProperty->setValue($importer, new SubjectEvent);

    $dataProperty = new ReflectionProperty(Importer::class, 'data');
    $dataProperty->setValue($importer, ['arm' => 'Unknown arm']);

    $originalDataProperty = new ReflectionProperty(Importer::class, 'originalData');
    $originalDataProperty->setValue($importer, ['Arm Name' => 'Unknown arm']);

    $eventColumn = collect($importer->getCachedColumns())
        ->first(fn ($column): bool => $column->getName() === 'event');

    $validator = Validator::make(
        ['event' => 'Unknown event'],
        ['event' => $eventColumn->getDataValidationRules()],
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('event'))
        ->toBe("The event 'Unknown event' does not exist in this project.");

    $dataProperty->setValue($importer, []);

    $validator = Validator::make(
        ['event' => 'Unknown event'],
        ['event' => $eventColumn->getDataValidationRules()],
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('event'))
        ->toBe("The event 'Unknown event' does not exist in this project.");
});
