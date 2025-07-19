<?php

namespace App\Filament\Resources\JobBatches;

use App\Filament\Resources\JobBatches\Pages\CreateJobBatch;
use App\Filament\Resources\JobBatches\Pages\EditJobBatch;
use App\Filament\Resources\JobBatches\Pages\ListJobBatches;
use App\Filament\Resources\JobBatches\Schemas\JobBatchForm;
use App\Filament\Resources\JobBatches\Tables\JobBatchesTable;
use App\Models\Controls\JobBatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobBatchResource extends Resource
{
    protected static ?string $model = JobBatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return JobBatchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobBatchesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobBatches::route('/'),
            'create' => CreateJobBatch::route('/create'),
            'edit' => EditJobBatch::route('/{record}/edit'),
        ];
    }
}
