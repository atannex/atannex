<?php

namespace App\Filament\Resources\CategorySections\Schemas;

use App\Models\Regions\Category;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategorySectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Configure the category and section relationship')
                    ->schema([

                        Select::make('category_id')
                            ->label('Category')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return Category::with('parent')
                                    ->get()
                                    ->mapWithKeys(function ($category) {
                                        $label = $category->parent
                                            ? sprintf('%s → %s', $category->parent->name, $category->name)
                                            : $category->name;

                                        return [$category->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->placeholder('Select a category')
                            ->helperText('Choose the parent category for this section (hierarchical display)')
                            ->native(false)
                            ->prefixIcon('heroicon-o-folder'),

                        Select::make('section_id')
                            ->label('Section')
                            ->relationship('section', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Select a section (optional)')
                            ->helperText('Associate with a specific section if needed')
                            ->native(false)
                            ->prefixIcon('heroicon-o-rectangle-stack'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
