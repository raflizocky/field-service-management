<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TaskStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Task Status Distribution';

    protected function getData(): array
    {
        $statuses = ['pending', 'in_progress', 'completed'];
        $data = [];

        foreach ($statuses as $status) {
            $data[] = Task::where('status', $status)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tasks',
                    'data' => $data,
                ],
            ],
            'labels' => ['Pending', 'In Progress', 'Completed'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
