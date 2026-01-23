<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ActiveUsersChart extends ChartWidget
{
    protected ?string $heading = 'Active Users Chart';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '500px';

    protected function getData(): array
    {
        $monthly = User::where('active', true)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as count")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        if (empty($monthly)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Active Users',
                        'data' => [],
                    ],
                ],
                'labels' => [],
            ];
        }

        $months = array_keys($monthly);
        $start = Carbon::createFromFormat('Y-m', $months[0])->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', end($months))->startOfMonth();

        $labels = [];
        $cumulative = [];
        $sum = 0;

        for ($date = $start; $date <= $end; $date = $date->copy()->addMonth()) {
            $m = $date->format('Y-m');
            $labels[] = $m;
            $sum += $monthly[$m] ?? 0;
            $cumulative[] = $sum;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $cumulative,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        // Force integer ticks on y-axis
                        'precision' => 0,
                        'stepSize' => 1,
                    ],
                ],
            ],
            // Keep legend visible by default
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
