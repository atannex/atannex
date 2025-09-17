<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use App\Models\Pages\Page;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\IconPosition;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array{
        return [
            'active' => Tab::make('Active Pages')
            ->icon('heroicon-m-check-circle')
            ->iconPosition(IconPosition::After)
            ->badge(Page::query()->where('is_active',true)->count())
            ->modifyQueryUsing(fn($query)=> $query->where('is_active',true)),

            'In-active' => Tab::make('In-Active Pages')
            ->icon('heroicon-o-x-circle')
            ->badge(Page::query()->where('is_active',false)->count())
            ->iconPosition(IconPosition::After)
            ->modifyQueryUsing(fn($query)=> $query->where('is_active',false))
        ];
    }
}
