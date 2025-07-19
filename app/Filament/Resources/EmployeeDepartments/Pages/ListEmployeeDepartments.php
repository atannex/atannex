<?php

namespace App\Filament\Resources\EmployeeDepartments\Pages;

use App\Filament\Resources\EmployeeDepartments\EmployeeDepartmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeDepartments extends ListRecords
{
    protected static string $resource = EmployeeDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
