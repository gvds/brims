<?php

namespace App\Filament\Project\Widgets;

use App\Models\Project;
use App\Models\Subject;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ProjectProgress extends ChartWidget
{
    protected ?string $heading = 'Cumulative Enrolment by Site';

    /** @var list<string> */
    private const SITE_COLOURS = [
        '#2563eb',
        '#dc2626',
        '#16a34a',
        '#d97706',
        '#9333ea',
        '#0891b2',
        '#db2777',
        '#65a30d',
        '#4b5563',
        '#ea580c',
    ];

    /**
     * @return array{datasets: list<array<string, mixed>>, labels: list<string>}
     */
    protected function getData(): array
    {
        $project = Project::find(session('currentProject')->id);

        /** @var array<int, array<string, int>> $dailyCounts site_id => [enrolDate => count] */
        $dailyCounts = Cache::remember(
            'subject_counts',
            120,
            fn () => Subject::withoutGlobalScopes()
                ->where('project_id', $project->id)
                ->whereNotNull('enrolDate')
                ->selectRaw('site_id, enrolDate, COUNT(*) as total')
                ->groupBy('site_id', 'enrolDate')
                ->get()
                ->groupBy('site_id')
                ->map(fn ($rows) => $rows->pluck('total', 'enrolDate')->map(fn ($total): int => (int) $total)->all())
                ->all()
        );

        $firstEnrolDate = collect($dailyCounts)->flatMap(fn (array $days): array => array_keys($days))->min();
        $start = CarbonImmutable::parse($project->submission_date ?? $firstEnrolDate ?? today())->startOfMonth();

        $monthEnds = [];
        for ($month = $start; $month->lessThanOrEqualTo(today()); $month = $month->addMonth()) {
            $monthEnds[] = $month->endOfMonth();
        }

        $datasets = [];
        foreach ($project->sites()->orderBy('name')->get() as $index => $site) {
            $days = $dailyCounts[$site->id] ?? [];
            $colour = self::SITE_COLOURS[$index % count(self::SITE_COLOURS)];

            $data = [];
            foreach ($monthEnds as $monthEnd) {
                $cutoff = $monthEnd->toDateString();
                $data[] = (int) collect($days)
                    ->filter(fn (int $total, string $date): bool => substr($date, 0, 10) <= $cutoff)
                    ->sum();
            }

            $datasets[] = [
                'label' => $site->name,
                'data' => $data,
                'borderColor' => $colour,
                'backgroundColor' => $colour,
                'borderWidth' => 2,
                'tension' => 0.2,
                'fill' => false,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => array_map(fn (CarbonImmutable $monthEnd): string => $monthEnd->format('M Y'), $monthEnds),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
