<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                // Main Content Group
                Group::make()
                    ->columnSpan(['lg' => 8])
                    ->schema([
                        // Color Information Section
                        Section::make('Color Information')
                            ->description('Define your color name and values')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Color Name')
                                            ->placeholder('Primary Blue')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->helperText('Enter a descriptive name for this color'),

                                        TextInput::make('hex')
                                            ->label('Hex Code')
                                            ->placeholder('#3B82F6')
                                            ->required()
                                            ->maxLength(7)
                                            ->prefix('#')
                                            ->regex('/^[0-9A-Fa-f]{6}$/')
                                            ->validationMessages([
                                                'regex' => 'Please enter a valid 6-digit hex color code (without #)',
                                            ])
                                            ->live(onBlur: true)
                                            ->helperText('Enter hex code without # symbol'),
                                    ]),
                            ]),
                    ]),

                // Sidebar Group
                Group::make()
                    ->columnSpan(['lg' => 4])
                    ->schema([
                        // Color Preview Section
                        Section::make('Color Preview')
                            ->description('Visual representation')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        ColorPicker::make('preview_color')
                                            ->label('Color Picker')
                                            ->formatStateUsing(fn ($get) => $get('hex') ? '#'.ltrim($get('hex'), '#') : '#000000')
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $hex = ltrim($state, '#');
                                                $set('hex', $hex);
                                            })
                                            ->helperText('Use picker or enter hex code manually'),
                                    ]),
                            ]),

                        // Color Information Section
                        Section::make('Details')
                            ->description('Additional information')
                            ->icon('heroicon-o-information-circle')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Placeholder::make('rgb_values')
                                            ->label('RGB Values')
                                            ->content(function ($get) {
                                                $hex = $get('hex') ?: '000000';
                                                $hex = ltrim($hex, '#');

                                                if (strlen($hex) === 6) {
                                                    $r = hexdec(substr($hex, 0, 2));
                                                    $g = hexdec(substr($hex, 2, 2));
                                                    $b = hexdec(substr($hex, 4, 2));

                                                    return sprintf('rgb(%s, %s, %s)', $r, $g, $b);
                                                }

                                                return 'Invalid hex code';
                                            }),

                                        Placeholder::make('hsl_values')
                                            ->label('HSL Values')
                                            ->content(function ($get) {
                                                $hex = $get('hex') ?: '000000';
                                                $hex = ltrim($hex, '#');

                                                if (strlen($hex) === 6) {
                                                    $r = hexdec(substr($hex, 0, 2)) / 255;
                                                    $g = hexdec(substr($hex, 2, 2)) / 255;
                                                    $b = hexdec(substr($hex, 4, 2)) / 255;

                                                    $max = max($r, $g, $b);
                                                    $min = min($r, $g, $b);
                                                    $l = ($max + $min) / 2;

                                                    if ($max === $min) {
                                                        $h = 0;
                                                        $s = 0;
                                                    } else {
                                                        $d = $max - $min;
                                                        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

                                                        switch ($max) {
                                                            case $r:
                                                                $h = (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6;
                                                                break;
                                                            case $g:
                                                                $h = (($b - $r) / $d + 2) / 6;
                                                                break;
                                                            case $b:
                                                                $h = (($r - $g) / $d + 4) / 6;
                                                                break;
                                                        }
                                                    }

                                                    $h = round($h * 360);
                                                    $s = round($s * 100);
                                                    $l = round($l * 100);

                                                    return sprintf('hsl(%s°, %s%%, %s%%)', $h, $s, $l);
                                                }

                                                return 'Invalid hex code';
                                            }),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
