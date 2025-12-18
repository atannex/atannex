<?php

namespace App\Filament\Widgets;

use App\Concerns\Analytics\CountsActiveUsers;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveUsers extends StatsOverviewWidget
{
    use CountsActiveUsers;

    protected function getStats(): array
    {
        return [
            Stat::make('Active Users', $this->countActiveUsers())
                ->description('Authenticated users online (last 5 minutes)')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Active Guests', $this->countActiveGuests())
                ->description('Guest sessions online (last 5 minutes)')
                ->descriptionIcon('heroicon-o-user')
                ->color('warning'),

            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
