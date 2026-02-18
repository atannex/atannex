<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Tag Form Schema
 *
 * Defines the form structure for creating and editing tags.
 * Includes automatic slug generation and comprehensive validation.
 */
class TagForm
{
    /**
     * Configure the tag form schema
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tag Information')
                    ->description('Enter the basic information for this tag')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tag Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter tag name')
                            ->helperText('The display name for this tag')
                            ->columnSpan(['sm' => 2, 'lg' => 1]),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->disabled()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('auto-generated-from-name')
                            ->helperText('URL-friendly version (auto-generated)')
                            ->alphaDash()
                            ->columnSpan(['sm' => 2, 'lg' => 1]),
                    ])
                    ->columns(['sm' => 2, 'lg' => 2])
                    ->columnSpanFull()
                    ->collapsible(),

                Section::make('Additional Details')
                    ->description('Optional information about this tag')
                    ->schema([
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->maxLength(500)
                            ->placeholder('Describe what this tag represents...')
                            ->helperText('Maximum 500 characters')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
