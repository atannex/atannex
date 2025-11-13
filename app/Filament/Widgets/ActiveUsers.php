<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Atannex\Concerns\HasUserTracking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveUsers extends StatsOverviewWidget
{
    use HasUserTracking;

    protected function getStats(): array
    {
        return [
            Stat::make('Active Users', $this->countActiveUsers())
                ->description('Users online in the last 5 minutes')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
