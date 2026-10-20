<?php

use App\Enums\SystemRoles;
use App\Filament\Imports\SubjectEventImporter;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;
use App\Models\Site;
use App\Models\Subject;
use App\Models\SubjectEvent;
use App\Models\Team;
use App\Models\User;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;

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

it('reuses relationship lookups and checks duplicates using foreign keys', function () {
    $user = User::factory()->create([
        'system_role' => SystemRoles::SuperAdmin,
    ]);
    $team = Team::factory()->create(['leader_id' => $user->id]);
    $user->update([
        'team_id' => $team->id,
        'team_role' => 'Admin',
    ]);

    actingAs($user);

    $project = Project::factory()->create([
        'team_id' => $team->id,
        'leader_id' => $user->id,
    ]);
    $site = Site::factory()->create(['project_id' => $project->id]);
    $arm = Arm::factory()->create([
        'project_id' => $project->id,
        'arm_num' => 1,
        'name' => 'Control',
    ]);
    $event = Event::factory()->create([
        'arm_id' => $arm->id,
        'name' => 'Baseline',
    ]);
    $subject = Subject::factory()->create([
        'subjectID' => 'SUB001',
        'project_id' => $project->id,
        'site_id' => $site->id,
        'user_id' => $user->id,
    ]);

    session(['currentProject' => $project]);

    $importer = new SubjectEventImporter(
        new Import,
        ['subjectID' => 'subjectID', 'arm' => 'arm', 'event' => 'event'],
        ['project' => $project],
    );
    $record = new SubjectEvent;

    $recordProperty = new ReflectionProperty(Importer::class, 'record');
    $recordProperty->setValue($importer, $record);

    $dataProperty = new ReflectionProperty(Importer::class, 'data');
    $dataProperty->setValue($importer, [
        'subjectID' => $subject->subjectID,
        'arm' => $arm->name,
        'event' => $event->name,
        'iteration' => 1,
    ]);

    $columns = collect($importer->getCachedColumns())->keyBy(
        fn ($column): string => $column->getName(),
    );
    $rules = collect(['subjectID', 'arm', 'event'])
        ->mapWithKeys(fn (string $column): array => [
            $column => $columns[$column]->getDataValidationRules(),
        ])
        ->all();
    $row = [
        'subjectID' => $subject->subjectID,
        'arm' => $arm->name,
        'event' => $event->name,
    ];

    $selectQueries = [];
    DB::listen(function (QueryExecuted $query) use (&$selectQueries): void {
        if (str_starts_with(strtolower($query->sql), 'select')) {
            $selectQueries[] = $query->sql;
        }
    });

    expect(Validator::make($row, $rules)->passes())->toBeTrue()
        ->and(Validator::make($row, $rules)->passes())->toBeTrue();

    $columns['subjectID']->fillRecord($subject->subjectID);
    $columns['event']->fillRecord($event->name);

    expect($record->subject_id)->toBe($subject->id)
        ->and($record->event_id)->toBe($event->id)
        ->and($selectQueries)->toHaveCount(3);

    SubjectEvent::query()->withoutGlobalScopes()->create([
        'subject_id' => $subject->id,
        'event_id' => $event->id,
        'iteration' => 1,
        'status' => 0,
        'labelstatus' => 0,
    ]);

    $selectQueriesBeforeDuplicateCheck = count($selectQueries);
    $afterValidate = new ReflectionMethod(SubjectEventImporter::class, 'afterValidate');
    $errors = [];

    try {
        $afterValidate->invoke($importer);
    } catch (ValidationException $exception) {
        $errors = $exception->errors();
    }

    expect($errors)->toBe([
        'event' => [
            "The combination of subject 'SUB001' arm 'Control' event 'Baseline' iteration 1 already exists.",
        ],
    ])->and(count($selectQueries))->toBe($selectQueriesBeforeDuplicateCheck + 1);
});
