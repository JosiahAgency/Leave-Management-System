<?php

namespace App\Filament\Resources\LeaveRequestResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserLeaveDaysCount extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Number of Leave Days Left:', auth()->user()?->leaveDays ?? 0),
        ];
    }
}
