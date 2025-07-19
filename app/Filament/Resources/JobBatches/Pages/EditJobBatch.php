<?php

namespace App\Filament\Resources\JobBatches\Pages;

use App\Filament\Resources\JobBatches\JobBatchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJobBatch extends EditRecord
{
    protected static string $resource = JobBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
