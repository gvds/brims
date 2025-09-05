<?php

/**
 * ArmsResource Comprehensive Test Suite
 *
 * This test suite provides comprehensive coverage for the ArmsResource functionality
 * using Pest v4 testing framework and modern testing patterns.
 *
 * Coverage includes:
 * - Model creation and relationships
 * - Business logic validation (arm numbering, manual enrollment, switchable arms)
 * - Data integrity and casting functionality
 * - Events relationship management
 *
 * Note: ArmsResource is a nested resource under ProjectResource that is managed
 * through both the Project resource's relation manager and dedicated resource pages.
 */

use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function (): void {
    actingAs($this->adminuser);

    // Create a test project for nested resource context
    $this->project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);
});

describe('Arms Model and Business Logic', function (): void {
    it('can create arms with required fields', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Control Arm',
            'arm_num' => 1,
            'manual_enrol' => true,
        ]);

        expect($arm->project_id)->toBe($this->project->id);
        expect($arm->name)->toBe('Control Arm');
        expect($arm->arm_num)->toBe(1);
        expect($arm->manual_enrol)->toBeTrue();
    });

    it('belongs to a project correctly', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
        ]);

        expect($arm->project)->toBeInstanceOf(Project::class);
        expect($arm->project->id)->toBe($this->project->id);
    });

    it('can have many events', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
        ]);

        $events = Event::factory()->count(3)->create([
            'arm_id' => $arm->id,
        ]);

        expect($arm->events)->toHaveCount(3);
        foreach ($events as $event) {
            expect($arm->events->contains($event))->toBeTrue();
        }
    });

    it('casts manual_enrol as boolean correctly', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'manual_enrol' => 1, // Integer value
        ]);

        $arm->refresh();
        expect($arm->manual_enrol)->toBeTrue();

        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'manual_enrol' => 0, // Integer value
        ]);

        $arm->refresh();
        expect($arm->manual_enrol)->toBeFalse();
    });

    it('casts switcharms as array correctly', function (): void {
        $controlArm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'arm_num' => 1,
            'name' => 'Control',
        ]);

        $treatmentArm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'arm_num' => 2,
            'name' => 'Treatment',
            'switcharms' => [$controlArm->id],
        ]);

        expect($treatmentArm->switcharms)->toBeArray();
        expect($treatmentArm->switcharms)->toContain($controlArm->id);
    });

    it('handles null switcharms correctly', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'switcharms' => null,
        ]);

        expect($arm->switcharms)->toBeNull();
    });

    it('ensures arm numbers can be unique within a project', function (): void {
        Arm::factory()->create([
            'project_id' => $this->project->id,
            'arm_num' => 1,
        ]);

        // This should be allowed in different projects
        $otherProject = Project::factory()->create([
            'team_id' => $this->team->id,
            'leader_id' => $this->adminuser->id,
        ]);

        Arm::factory()->create([
            'project_id' => $otherProject->id,
            'arm_num' => 1, // Same arm number but different project
        ]);

        expect($this->project->arms()->where('arm_num', 1)->count())->toBe(1);
        expect($otherProject->arms()->where('arm_num', 1)->count())->toBe(1);
    });

    it('can be deleted when no events exist', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
        ]);

        $armId = $arm->id;

        $arm->delete();

        assertDatabaseMissing('arms', ['id' => $armId]);
    });
});

describe('Arms Events Relationship', function (): void {
    beforeEach(function (): void {
        $this->arm = Arm::factory()->create([
            'project_id' => $this->project->id,
        ]);
    });

    it('can create events for an arm', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Baseline Visit',
            'offset' => 0,
        ]);

        expect($event->arm_id)->toBe($this->arm->id);
        $this->arm->refresh(); // Refresh to load the relationship
        expect($this->arm->events->contains($event))->toBeTrue();
    });

    it('can have multiple events with different offsets', function (): void {
        $events = [
            Event::factory()->create([
                'arm_id' => $this->arm->id,
                'name' => 'Baseline',
                'offset' => 0,
            ]),
            Event::factory()->create([
                'arm_id' => $this->arm->id,
                'name' => 'Week 4',
                'offset' => 28,
            ]),
            Event::factory()->create([
                'arm_id' => $this->arm->id,
                'name' => 'Week 8',
                'offset' => 56,
            ]),
        ];

        expect($this->arm->events)->toHaveCount(3);

        foreach ($events as $event) {
            expect($this->arm->events->contains($event))->toBeTrue();
        }
    });

    it('events belong to the correct arm', function (): void {
        $otherArm = Arm::factory()->create([
            'project_id' => $this->project->id,
        ]);

        $event1 = Event::factory()->create(['arm_id' => $this->arm->id]);
        $event2 = Event::factory()->create(['arm_id' => $otherArm->id]);

        $this->arm->refresh();
        $otherArm->refresh();

        expect($this->arm->events->contains($event1))->toBeTrue();
        expect($this->arm->events->contains($event2))->toBeFalse();
        expect($otherArm->events->contains($event2))->toBeTrue();
        expect($otherArm->events->contains($event1))->toBeFalse();
    });
});

describe('Arms Data Integrity', function (): void {
    it('requires project_id', function (): void {
        expect(function (): void {
            Arm::create([
                'name' => 'Test Arm',
                'arm_num' => 1,
            ]);
        })->toThrow(\Exception::class);
    });

    it('can be created with minimal required data', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Minimal Arm',
            'arm_num' => 1,
        ]);

        assertDatabaseHas('arms', [
            'id' => $arm->id,
            'project_id' => $this->project->id,
            'name' => 'Minimal Arm',
            'arm_num' => 1,
        ]);
    });

    it('handles multiple switchable arms', function (): void {
        $controlArm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'arm_num' => 1,
            'name' => 'Control',
        ]);

        $placeboArm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'arm_num' => 2,
            'name' => 'Placebo',
        ]);

        $treatmentArm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'arm_num' => 3,
            'name' => 'Treatment',
            'switcharms' => [$controlArm->id, $placeboArm->id],
        ]);

        expect($treatmentArm->switcharms)->toBeArray();
        expect($treatmentArm->switcharms)->toHaveCount(2);
        expect($treatmentArm->switcharms)->toContain($controlArm->id);
        expect($treatmentArm->switcharms)->toContain($placeboArm->id);
    });

    it('can update arm properties', function (): void {
        $arm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Original Name',
            'manual_enrol' => false,
        ]);

        $arm->update([
            'name' => 'Updated Name',
            'manual_enrol' => true,
        ]);

        assertDatabaseHas('arms', [
            'id' => $arm->id,
            'name' => 'Updated Name',
            'manual_enrol' => true,
        ]);
    });
});
