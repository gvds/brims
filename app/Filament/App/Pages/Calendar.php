<?php

namespace App\Filament\App\Pages;

use App\Enums\EventStatus;
use App\Models\Project;
use App\Models\SubjectEvent;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Calendar extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.app.pages.calendar';

    protected array $substitutees;
    protected array $user_ids;
    public Collection $projects;
    public array $colours = [];

    public array $subjectEvents;

    public static function canAccess(): bool
    {
        return Auth::user()->can('Manage:Subject');
    }

    public function mount(): void
    {
        $this->substitutees = Auth::user()->substitutees()->pluck('users.id')->toArray();
        $this->user_ids = array_merge([$user_id = Auth::id()], $this->substitutees);

        $this->subjectEvents = SubjectEvent::query()
            ->with(['subject.project', 'event.arm'])
            ->whereHas('user', function ($query) {
                $query->whereIn('users.id', $this->user_ids);
            })
            ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
            ->where('maxDate', '>=', now())
            ->where('eventDate', '<=', now()->addDays(30))
            ->orderBy('eventDate', 'asc')
            ->get()
            ->groupBy('eventDate')
            ->toArray();

        $this->projects = Project::where('active', true)
            ->whereHas(
                'subjects',
                function ($query) {
                    $query->whereIn('user_id', $this->user_ids);
                }
            )->get();

        $this->colours = $this->generateDistinctColors($this->projects->count());
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


    public function table(Table $table): Table
    {
        return $table
            ->query(
                SubjectEvent::query()
                    ->with(['subject', 'event.arm'])
                    ->whereHas('user', function ($query) {
                        $query->whereIn('users.id', $this->user_ids);
                    })
                    ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                    ->where('maxDate', '>=', now())
                    ->where('eventDate', '<=', now()->addDays(30))
                    ->orderBy('eventDate', 'asc')
            )
            ->columns([
                Stack::make([
                    TextColumn::make('subject.fullname')
                        ->label('Subject'),
                    TextColumn::make('event.arm.name')
                        ->prefix('Arm: '),
                    TextColumn::make('event.name')
                        ->prefix('Event: '),
                ])
                    ->extraAttributes(fn(SubjectEvent $record) => [
                        'class' => '!items-start border-3 rounded-md px-2 py-1',
                        'style' => 'color: ' . $this->colours[$record->subject->project->id]
                    ]),
            ])
            ->defaultGroup(
                Group::make('eventDate')
                    ->label('Event Date')
                    ->getTitleFromRecordUsing(fn($record): string => Carbon::parse($record->eventDate)->format('l, d M Y'))
                    ->titlePrefixedWithLabel(false)
            )
            ->contentGrid([
                'sm' => 2,
                'md' => 3,
                'xl' => 4,
                '2xl' => 5,
                '3xl' => 6
            ])
            ->filters([
                //
            ]);
    }
}
