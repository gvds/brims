<?php

namespace App\Filament\App\Pages;

use App\Enums\EventStatus;
use App\Models\Project;
use App\Models\SubjectEvent;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Calendar extends Page
{

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.app.pages.calendar';

    protected array $user_ids;
    public Collection $projects;
    public array $colours = [];

    public array $subjectEvents;

    public Carbon $date;
    public ?string $month;
    public int $year;
    public array $weeks = [];

    public static function canAccess(): bool
    {
        return Auth::user()->can('Manage:Subject');
    }

    public function mount(): void
    {
        $this->date = now();

        $this->year = $this->date->year;
        $this->month = $this->date->monthName;

        $this->getSubjectEvents();

        $this->projects = Project::where('active', true)
            ->whereHas(
                'subjects',
                function ($query) {
                    $query->whereIn('user_id', $this->user_ids);
                }
            )->get();

        $this->colours = $this->generateDistinctColors($this->projects->count());

        $this->generateCalendar();
    }

    private function getSubjectEvents()
    {
        $substitutees = Auth::user()->substitutees()->pluck('users.id')->toArray();
        $this->user_ids = array_merge([$user_id = Auth::id()], $substitutees);

        $this->subjectEvents = SubjectEvent::query()
            ->with(['subject.project', 'event.arm'])
            ->whereHas('user', function ($query) {
                $query->whereIn('users.id', $this->user_ids);
            })
            ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
            ->where('maxDate', '>=', $this->date)
            ->where('eventDate', '<=', $this->date->copy()->addDays(30))
            ->orderBy('eventDate', 'asc')
            ->get()
            ->groupBy('eventDate')
            ->toArray();
    }

    private function generateCalendar()
    {
        $date = $this->date;
        $startOfMonth = $date->startOfMonth();
        $startWeek = $startOfMonth->weekOfYear;
        $weekStartDate = Carbon::now()->setISODate($this->year, $startWeek, 1)->startOfWeek(Carbon::SUNDAY);
        $endOfMonth = $date->endOfMonth();

        $this->weeks = [];

        // dd($this->subjectEvents);
        while ($weekStartDate->lte($endOfMonth)) {
            $this->weeks[] = [
                'start' => $weekStartDate->copy(),
                'end' => $weekStartDate->copy()->endOfWeek(Carbon::SATURDAY),
                'events' => []
            ];
            $weekStartDate->addWeek();
        }
    }

    private function generateDistinctColors($count = 1)
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            // Calculate hue, keeping saturation (100%) and lightness (50%) fixed
            $hue = ($i * (360 / $count)) % 360;
            $saturation = 1; // 70%
            $lightness = 0.5;  // 50%

            // Convert HSL to RGB
            $c = $lightness * $saturation;
            $x = $c * (1 - abs(fmod($hue / 60, 2) - 1));
            $m = $lightness - ($c / 2);

            // Determine base RGB ratios using PHP match expression
            [$r_base, $g_base, $b_base] = match (true) {
                $hue < 60   => [$c, $x, 0],
                $hue < 120  => [$x, $c, 0],
                $hue < 180  => [0, $c, $x],
                $hue < 240  => [0, $x, $c],
                $hue < 300  => [$x, 0, $c],
                default     => [$c, 0, $x],
            };

            // Convert to 8-bit integer values
            $r = round(($r_base + $m) * 255);
            $g = round(($g_base + $m) * 255);
            $b = round(($b_base + $m) * 255);

            // Convert RGB to Hex and store
            $colors[$this->projects[$i]->id] = sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        return $colors;
    }

    public function nextmonth()
    {
        $this->date->addMonth();
        $this->getSubjectEvents();
        $this->generateCalendar();
    }

    public function previousmonth()
    {
        $this->date->subMonth();
        $this->getSubjectEvents();
        $this->generateCalendar();
    }
}
