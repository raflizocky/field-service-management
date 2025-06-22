<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TaskStatusChart extends ChartWidget
{
    protected function getData(): array
    {
        $data = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Number of Tasks',
                    'data' => [
                        $data['pending'] ?? 0,
                        $data['in_progress'] ?? 0,
                        $data['completed'] ?? 0,
                    ],
                    'backgroundColor' => ['#facc15', '#f97316', '#22c55e'],
                ],
            ],
            'labels' => ['Pending', 'In Progress', 'Completed'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
