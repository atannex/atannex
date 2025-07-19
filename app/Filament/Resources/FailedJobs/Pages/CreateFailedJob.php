<?php

namespace App\Filament\Resources\FailedJobs\Pages;

use App\Filament\Resources\FailedJobs\FailedJobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFailedJob extends CreateRecord
{
    protected static string $resource = FailedJobResource::class;
}
