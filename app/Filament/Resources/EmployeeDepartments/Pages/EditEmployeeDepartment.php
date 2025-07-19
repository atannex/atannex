<?php

namespace App\Filament\Resources\EmployeeDepartments\Pages;

use App\Filament\Resources\EmployeeDepartments\EmployeeDepartmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeDepartment extends EditRecord
{
    protected static string $resource = EmployeeDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
