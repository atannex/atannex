<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveUsers extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Users', \App\Models\UserLogs::count())
                ->description('Currently Online')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),
            Stat::make('Total Users', \App\Models\User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            // --- IGNORE ---
        ];
    }
}
