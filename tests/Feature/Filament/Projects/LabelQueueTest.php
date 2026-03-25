<?php

use App\Enums\LabelStatus;
use App\Enums\SubjectStatus;
use App\Filament\Project\Pages\LabelQueue;
use App\Library\PDF_Label;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;
use App\Models\Subject;
use App\Models\SubjectEvent;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = $this->adminuser;
    actingAs($this->user);

    $this->project = Project::factory()
        ->for($this->team)
        ->for($this->user, 'leader')
        ->hasSites(1)
        ->create();

    $this->site = $this->project->sites->first();

    // attach user to project
    $this->project->members()->attach($this->user->id, [
        'site_id' => $this->site->id,
        'role_id' => 'Admin',
    ]);

    // Subject + arm + event
    $this->subject = Subject::factory()
        ->for($this->project)
        ->for($this->site, 'site')
        ->for($this->user)
        ->create([
            'subjectID' => 'T-001',
            'status' => SubjectStatus::Enrolled->value,
        ]);

    $this->arm = Arm::factory()->for($this->project)->create(['arm_num' => 1]);
    $this->event = Event::factory()->for($this->arm)->create();

    $this->subjectEvent = SubjectEvent::create([
        'subject_id' => $this->subject->id,
        'event_id' => $this->event->id,
        'status' => 0,
        'labelstatus' => LabelStatus::Queued->value,
        'eventDate' => now()->addWeek(),
        'minDate' => now(),
        'active' => true,
    ]);

    if (! defined('FPDF_FONTPATH')) {
        define('FPDF_FONTPATH', base_path('packages/codedge/laravel-fpdf/src/Fpdf/font/'));
    }

    Session::put('currentProject', $this->project);
});

it('loads the label queue page and shows queued events', function (): void {
    livewire(LabelQueue::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$this->subjectEvent])
        // ensure the single-record print action is rendered as a downloadable URL (prevents Livewire from returning binary)
        ->assertSee(route('labels.print', ['ids' => [$this->subjectEvent->id]]))
        // header action: print all labels for the project
        ->assertSee(route('labels.print'));
});

it('can clear a single queued label from the queue', function (): void {
    $component = livewire(LabelQueue::class)
        ->assertCanSeeTableRecords([$this->subjectEvent]);

    $component->callAction(TestAction::make('clear')->table($this->subjectEvent))
        ->assertStatus(200);

    $this->assertDatabaseHas('subject_event', [
        'id' => $this->subjectEvent->id,
        'labelstatus' => LabelStatus::Generated->value,
    ]);

    $component->assertCanNotSeeTableRecords([$this->subjectEvent]);
});

it('redirects to the print route with selected ids when bulk-printing', function (): void {
    $component = livewire(LabelQueue::class)
        ->assertCanSeeTableRecords([$this->subjectEvent]);

    $component->selectTableRecords([$this->subjectEvent->id])
        ->callAction(TestAction::make('printSelected')->table()->bulk())
        ->assertRedirect(route('labels.print', ['ids' => [$this->subjectEvent->id]]));
});

it('renders when labelstatus is enum instance and when it is a string/backing-value', function (): void {
    // enum-instance (Eloquent casts may return BackedEnum in some contexts)
    $enumRecord = SubjectEvent::create([
        'subject_id' => $this->subject->id,
        'event_id' => $this->event->id,
        'iteration' => 2,
        'status' => 0,
        'labelstatus' => LabelStatus::Queued->value,
        'eventDate' => now()->addDays(2),
        'minDate' => now(),
        'active' => true,
    ]);

    // string-backed value (simulates other code paths that may pass strings)
    $stringRecord = SubjectEvent::create([
        'subject_id' => $this->subject->id,
        'event_id' => $this->event->id,
        'iteration' => 3,
        'status' => 0,
        'labelstatus' => (string) LabelStatus::Queued->value,
        'eventDate' => now()->addDays(3),
        'minDate' => now(),
        'active' => true,
    ]);

    livewire(LabelQueue::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$enumRecord, $stringRecord]);
});

it('returns a PDF download when printing selected subject-event(s)', function (): void {
    $fakePdf = new class extends PDF_Label
    {
        public function __construct() {}

        public function AddPage($orientation = '', $size = '', $rotation = 0) {}

        public function AddFont($family, $style = '', $file = '', $dir = '') {}

        public function SetFont($family, $style = '', $size = 0) {}

        public function SetMargins($left, $top, $right = null) {}

        public function SetAutoPageBreak($auto, $margin = 0) {}

        public function Add_BarLabel($text, $code = null) {}

        public function Output($dest = '', $name = '', $isUTF8 = false)
        {
            return '%PDF';
        }
    };

    $this->instance(PDF_Label::class, $fakePdf);

    $response = $this->get(route('labels.print', ['ids' => [$this->subjectEvent->id]]));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');

    // basic sanity: PDF files start with "%PDF"
    expect(substr($response->getContent(), 0, 4))->toBe('%PDF');
});
