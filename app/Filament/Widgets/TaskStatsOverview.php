<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TaskStatsOverview extends BaseWidget
{
    protected static ?string $maxWidth = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Tasks', Task::count()),
            Stat::make('Completed Tasks', Task::where('status', 'completed')->count()),
            Stat::make('Reports Submitted', Report::count()),
        ];
    }
}
