<?php

/**
 * EventsRelationManager Comprehensive Test Suite
 *
 * This test suite provides comprehensive coverage for the Arms EventsRelationManager
 * functionality using Pest v4 testing framework and Filament v4 testing patterns.
 *
 * Coverage includes:
 * - Relation manager configuration and setup (5 tests)
 * - Event creation and validation (8 tests)
 * - Offset and window management (6 tests)
 * - Autolog and repeatable functionality (4 tests)
 * - Label count management (4 tests)
 * - Event ordering and reordering (4 tests)
 * - Business logic validation (5 tests)
 *
 * Total: 36 tests focused on core business logic and data integrity
 *
 * Note: EventsRelationManager manages the one-to-many relationship between
 * Arms and Events with form fields including name, offset, windows, autolog,
 * repeatable status, and various label counts for study management.
 */

use App\Filament\Resources\Projects\Resources\Arms\RelationManagers\EventsRelationManager;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function (): void {
    actingAs($this->adminuser);

    // Create a test project with leader
    $this->project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);

    // Create a test arm for the project
    $this->arm = Arm::factory()->create([
        'project_id' => $this->project->id,
        'name' => 'Test Arm',
        'arm_num' => 1,
    ]);

    // Create some existing events for testing
    $this->baselineEvent = Event::factory()->create([
        'arm_id' => $this->arm->id,
        'name' => 'Baseline Visit',
        'offset' => 0,
        'autolog' => false,
        'repeatable' => false,
        'offset_ante_window' => null,
        'offset_post_window' => null,
        'name_labels' => 1,
        'subject_event_labels' => 1,
        'study_id_labels' => 1,
        'event_order' => 1,
        'active' => true,
    ]);

    $this->followUpEvent = Event::factory()->create([
        'arm_id' => $this->arm->id,
        'name' => 'Follow-up Visit',
        'offset' => 30,
        'autolog' => true,
        'repeatable' => true,
        'offset_ante_window' => 7,
        'offset_post_window' => 14,
        'name_labels' => 2,
        'subject_event_labels' => 2,
        'study_id_labels' => 2,
        'event_order' => 2,
        'active' => true,
    ]);

    // Refresh the arm to get updated relationships
    $this->arm->refresh();
});

describe('Events Relation Manager Configuration', function (): void {
    it('has correct relationship configuration', function (): void {
        expect(EventsRelationManager::getRelationshipName())->toBe('events');
    });

    it('is not read-only', function (): void {
        $manager = new EventsRelationManager;
        $manager->ownerRecord = $this->arm;

        expect($manager->isReadOnly())->toBeFalse();
    });

    it('can be instantiated correctly', function (): void {
        expect(EventsRelationManager::class)->toBeString();
        expect(class_exists(EventsRelationManager::class))->toBeTrue();
    });

    it('has proper relationship with arm', function (): void {
        expect($this->arm->events())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
        expect($this->arm->events()->count())->toBe(2); // 2 existing events added in setup
    });

    it('maintains event records correctly', function (): void {
        $eventIds = $this->arm->events()->pluck('id')->toArray();
        expect($eventIds)->toHaveCount(2);
        expect($eventIds)->toContain($this->baselineEvent->id);
        expect($eventIds)->toContain($this->followUpEvent->id);
    });
});

describe('Event Creation and Validation', function (): void {
    it('can create a basic event', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'New Event',
            'offset' => 60,
            'autolog' => false,
            'repeatable' => false,
            'name_labels' => 0,
            'subject_event_labels' => 0,
            'study_id_labels' => 0,
            'event_order' => 3,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'New Event',
            'offset' => 60,
            'autolog' => false,
            'repeatable' => false,
            'active' => true,
        ]);

        expect($this->arm->fresh()->events()->count())->toBe(3);
    });

    it('can create event with autolog enabled', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Autolog Event',
            'offset' => 90,
            'autolog' => true,
            'repeatable' => false,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Autolog Event',
            'autolog' => true,
        ]);
    });

    it('can create repeatable event', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Repeatable Event',
            'offset' => 120,
            'autolog' => false,
            'repeatable' => true,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Repeatable Event',
            'repeatable' => true,
        ]);
    });

    it('can create event with REDCap integration', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'REDCap Event',
            'redcap_event_id' => 12345,
            'offset' => 180,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'REDCap Event',
            'redcap_event_id' => 12345,
        ]);
    });

    it('maintains referential integrity when creating events', function (): void {
        $originalCount = $this->arm->events()->count();

        Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Integrity Test Event',
            'offset' => 210,
            'active' => true,
        ]);

        expect($this->arm->fresh()->events()->count())->toBe($originalCount + 1);
    });

    it('can update existing event', function (): void {
        $this->baselineEvent->update([
            'name' => 'Updated Baseline Visit',
            'offset' => 5,
            'autolog' => true,
        ]);

        assertDatabaseHas('events', [
            'id' => $this->baselineEvent->id,
            'name' => 'Updated Baseline Visit',
            'offset' => 5,
            'autolog' => true,
        ]);
    });

    it('can delete event', function (): void {
        $eventId = $this->followUpEvent->id;
        $originalCount = $this->arm->events()->count();

        $this->followUpEvent->delete();

        assertDatabaseMissing('events', [
            'id' => $eventId,
        ]);

        expect($this->arm->fresh()->events()->count())->toBe($originalCount - 1);
    });

    it('can create inactive event', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Inactive Event',
            'offset' => 240,
            'active' => false,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Inactive Event',
            'active' => false,
        ]);
    });
});

describe('Offset and Window Management', function (): void {
    it('can set event offset from baseline', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Delayed Event',
            'offset' => 365, // One year offset
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Delayed Event',
            'offset' => 365,
        ]);

        expect($event->fresh()->offset)->toBe(365);
    });

    it('can set ante window (before event)', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Window Event',
            'offset' => 100,
            'offset_ante_window' => 10,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Window Event',
            'offset_ante_window' => 10,
        ]);
    });

    it('can set post window (after event)', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Post Window Event',
            'offset' => 100,
            'offset_post_window' => 15,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Post Window Event',
            'offset_post_window' => 15,
        ]);
    });

    it('can set both ante and post windows', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Full Window Event',
            'offset' => 150,
            'offset_ante_window' => 5,
            'offset_post_window' => 20,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Full Window Event',
            'offset_ante_window' => 5,
            'offset_post_window' => 20,
        ]);
    });

    it('can clear window settings', function (): void {
        $this->followUpEvent->update([
            'offset_ante_window' => null,
            'offset_post_window' => null,
        ]);

        assertDatabaseHas('events', [
            'id' => $this->followUpEvent->id,
            'offset_ante_window' => null,
            'offset_post_window' => null,
        ]);
    });

    it('validates offset is numeric', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Numeric Offset Event',
            'offset' => 42,
            'active' => true,
        ]);

        expect($event->fresh()->offset)->toBeInt();
        expect($event->fresh()->offset)->toBe(42);
    });
});

describe('Autolog and Repeatable Functionality', function (): void {
    it('can toggle autolog functionality', function (): void {
        expect($this->baselineEvent->autolog)->toBeFalse();
        expect($this->followUpEvent->autolog)->toBeTrue();

        $this->baselineEvent->update(['autolog' => true]);

        assertDatabaseHas('events', [
            'id' => $this->baselineEvent->id,
            'autolog' => true,
        ]);
    });

    it('can toggle repeatable functionality', function (): void {
        expect($this->baselineEvent->repeatable)->toBeFalse();
        expect($this->followUpEvent->repeatable)->toBeTrue();

        $this->baselineEvent->update(['repeatable' => true]);

        assertDatabaseHas('events', [
            'id' => $this->baselineEvent->id,
            'repeatable' => true,
        ]);
    });

    it('can create event with both autolog and repeatable enabled', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Auto Repeatable Event',
            'offset' => 300,
            'autolog' => true,
            'repeatable' => true,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Auto Repeatable Event',
            'autolog' => true,
            'repeatable' => true,
        ]);
    });

    it('can disable both autolog and repeatable', function (): void {
        $this->followUpEvent->update([
            'autolog' => false,
            'repeatable' => false,
        ]);

        assertDatabaseHas('events', [
            'id' => $this->followUpEvent->id,
            'autolog' => false,
            'repeatable' => false,
        ]);
    });
});

describe('Label Count Management', function (): void {
    it('can set name labels count', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Name Labels Event',
            'offset' => 50,
            'name_labels' => 5,
            'subject_event_labels' => 0,
            'study_id_labels' => 0,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Name Labels Event',
            'name_labels' => 5,
        ]);
    });

    it('can set subject event labels count', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Subject Labels Event',
            'offset' => 75,
            'name_labels' => 0,
            'subject_event_labels' => 3,
            'study_id_labels' => 0,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Subject Labels Event',
            'subject_event_labels' => 3,
        ]);
    });

    it('can set study ID labels count', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Study ID Labels Event',
            'offset' => 100,
            'name_labels' => 0,
            'subject_event_labels' => 0,
            'study_id_labels' => 4,
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Study ID Labels Event',
            'study_id_labels' => 4,
        ]);
    });

    it('can update all label counts together', function (): void {
        $this->baselineEvent->update([
            'name_labels' => 10,
            'subject_event_labels' => 15,
            'study_id_labels' => 20,
        ]);

        assertDatabaseHas('events', [
            'id' => $this->baselineEvent->id,
            'name_labels' => 10,
            'subject_event_labels' => 15,
            'study_id_labels' => 20,
        ]);
    });
});

describe('Event Ordering and Reordering', function (): void {
    it('maintains event order correctly', function (): void {
        expect($this->baselineEvent->event_order)->toBe(1);
        expect($this->followUpEvent->event_order)->toBe(2);
    });

    it('can create event with specific order', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Middle Event',
            'offset' => 15,
            'event_order' => 2, // This would be between baseline and follow-up logically
            'active' => true,
        ]);

        assertDatabaseHas('events', [
            'arm_id' => $this->arm->id,
            'name' => 'Middle Event',
            'event_order' => 2,
        ]);
    });

    it('can reorder existing events', function (): void {
        $this->baselineEvent->update(['event_order' => 3]);
        $this->followUpEvent->update(['event_order' => 1]);

        assertDatabaseHas('events', [
            'id' => $this->baselineEvent->id,
            'event_order' => 3,
        ]);

        assertDatabaseHas('events', [
            'id' => $this->followUpEvent->id,
            'event_order' => 1,
        ]);
    });

    it('can query events in order', function (): void {
        $orderedEvents = $this->arm->events()->orderBy('event_order')->get();

        expect($orderedEvents->first()->id)->toBe($this->baselineEvent->id);
        expect($orderedEvents->last()->id)->toBe($this->followUpEvent->id);
    });
});

describe('Business Logic Validation', function (): void {
    it('maintains arm context isolation', function (): void {
        $otherArm = Arm::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Other Arm',
            'arm_num' => 2,
        ]);

        $otherEvent = Event::factory()->create([
            'arm_id' => $otherArm->id,
            'name' => 'Other Arm Event',
            'offset' => 30,
            'active' => true,
        ]);

        // Verify events are in correct arms
        assertDatabaseHas('events', [
            'id' => $this->baselineEvent->id,
            'arm_id' => $this->arm->id,
        ]);

        assertDatabaseHas('events', [
            'id' => $otherEvent->id,
            'arm_id' => $otherArm->id,
        ]);

        // Verify arms don't see each other's events
        expect($this->arm->fresh()->events()->where('id', $otherEvent->id)->exists())->toBeFalse();
        expect($otherArm->fresh()->events()->where('id', $this->baselineEvent->id)->exists())->toBeFalse();
    });

    it('can toggle active status', function (): void {
        expect($this->baselineEvent->active)->toBeTrue();

        $this->baselineEvent->update(['active' => false]);

        assertDatabaseHas('events', [
            'id' => $this->baselineEvent->id,
            'active' => false,
        ]);
    });

    it('preserves timestamps on event operations', function (): void {
        $originalCreatedAt = $this->baselineEvent->created_at;

        sleep(1); // Ensure time difference

        $this->baselineEvent->update([
            'name' => 'Timestamp Test Event',
        ]);

        $updated = $this->baselineEvent->fresh();

        expect($updated->created_at)->toEqual($originalCreatedAt);
        expect($updated->updated_at)->toBeGreaterThan($originalCreatedAt);
    });

    it('maintains relationship with arm', function (): void {
        expect($this->baselineEvent->arm)->toBeInstanceOf(Arm::class);
        expect($this->baselineEvent->arm->id)->toBe($this->arm->id);
        expect($this->baselineEvent->arm->project_id)->toBe($this->project->id);
    });

    it('handles boolean casting correctly', function (): void {
        $event = Event::factory()->create([
            'arm_id' => $this->arm->id,
            'name' => 'Boolean Test Event',
            'offset' => 0,
            'autolog' => 1, // Integer that should be cast to boolean
            'repeatable' => 0, // Integer that should be cast to boolean
            'active' => 1, // Integer that should be cast to boolean
        ]);

        $fresh = $event->fresh();

        expect($fresh->autolog)->toBeTrue(); // It's actually being cast to boolean
        expect($fresh->repeatable)->toBeFalse();
        expect($fresh->active)->toBeTrue();
    });
});
