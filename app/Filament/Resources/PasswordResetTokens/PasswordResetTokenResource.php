<?php

namespace App\Filament\Resources\PasswordResetTokens;

use App\Filament\Resources\PasswordResetTokens\Pages\CreatePasswordResetToken;
use App\Filament\Resources\PasswordResetTokens\Pages\EditPasswordResetToken;
use App\Filament\Resources\PasswordResetTokens\Pages\ListPasswordResetTokens;
use App\Filament\Resources\PasswordResetTokens\Schemas\PasswordResetTokenForm;
use App\Filament\Resources\PasswordResetTokens\Tables\PasswordResetTokensTable;
use App\Models\Controls\PasswordResetToken;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PasswordResetTokenResource extends Resource
{
    protected static ?string $model = PasswordResetToken::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PasswordResetTokenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PasswordResetTokensTable::configure($table);
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
            'index' => ListPasswordResetTokens::route('/'),
            'create' => CreatePasswordResetToken::route('/create'),
            'edit' => EditPasswordResetToken::route('/{record}/edit'),
        ];
    }
}
