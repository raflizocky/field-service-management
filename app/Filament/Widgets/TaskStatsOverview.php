<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TaskStatsOverview extends BaseWidget
{
    protected static ?string $maxWidth = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Pending Tasks', Task::where('status', 'pending')->count())
                ->color('gray'),

            Stat::make('In Progress Tasks', Task::where('status', 'in_progress')->count())
                ->color('warning'),

            Stat::make('Completed Tasks', Task::where('status', 'completed')->count())
                ->color('success'),
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
