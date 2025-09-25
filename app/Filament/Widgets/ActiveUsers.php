<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use App\Models\User;
use App\Models\Users\UserLogs;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveUsers extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Users', UserLogs::count())
                ->description('Currently Online')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
