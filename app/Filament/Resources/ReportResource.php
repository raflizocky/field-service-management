<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Resource;
use App\Filament\Resources\ReportResource\Pages;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Select::make('task_id')
                        ->relationship('task', 'title')
                        ->label('Task')
                        ->required()
                        ->searchable(),

                    Select::make('technician_id')
                        ->relationship('technician', 'name')
                        ->label('Technician')
                        ->required()
                        ->searchable(),
                ]),

                Select::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required()
                    ->default('completed'),

                Textarea::make('notes')
                    ->rows(4)
                    ->label('Notes')
                    ->columnSpanFull(),

                FileUpload::make('photo_path')
                    ->label('Photo')
                    ->image()
                    ->directory('reports')
                    ->columnSpanFull(),

                TextInput::make('gps_location')
                    ->label('GPS Location (Google Maps Link)')
                    ->placeholder('Paste Google Maps link here...')
                    ->helperText('Paste a Google Maps link and it will be converted to coordinates')
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $coords = self::extractCoordinatesFromGoogleMapsLink($state);
                            if ($coords) {
                                $set('gps_lat', $coords['lat']);
                                $set('gps_lng', $coords['lng']);
                            }
                        }
                    })
                    ->afterStateHydrated(function ($component, $state, $record) {
                        // When editing, populate the Google Maps link from existing coordinates
                        if ($record && $record->gps_lat && $record->gps_lng) {
                            $component->state("https://maps.google.com/?q={$record->gps_lat},{$record->gps_lng}");
                        }
                    })
                    ->live()
                    ->dehydrated(false)
                    ->columnSpanFull(),

                Grid::make(2)->schema([
                    TextInput::make('gps_lat')
                        ->label('Latitude')
                        ->numeric()
                        ->step(0.0000001)
                        ->readonly()
                        ->placeholder('Auto-filled from Google Maps link'),

                    TextInput::make('gps_lng')
                        ->label('Longitude')
                        ->numeric()
                        ->step(0.0000001)
                        ->readonly()
                        ->placeholder('Auto-filled from Google Maps link'),
                ]),

                DateTimePicker::make('submitted_at')
                    ->label('Submitted At')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task.title')
                    ->label('Task')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('technician.name')
                    ->label('Technician')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'completed' => 'success',
                        'failed' => 'danger',
                    }),

                TextColumn::make('notes')
                    ->limit(30),

                ImageColumn::make('photo_path')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('gps_location')
                    ->label('GPS')
                    ->getStateUsing(function ($record) {
                        if ($record->gps_lat && $record->gps_lng) {
                            return "{$record->gps_lat}, {$record->gps_lng}";
                        }
                        return 'No location';
                    })
                    ->icon(fn($record) => $record->gps_lat && $record->gps_lng ? 'heroicon-o-map-pin' : 'heroicon-o-x-mark')
                    ->tooltip('Click to view on Google Maps')
                    ->url(fn($record) => $record->gps_lat && $record->gps_lng
                        ? "https://maps.google.com/?q={$record->gps_lat},{$record->gps_lng}"
                        : null)
                    ->openUrlInNewTab(),

                TextColumn::make('submitted_at')
                    ->label('Submitted At')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),

                Tables\Filters\SelectFilter::make('technician_id')
                    ->label('Technician')
                    ->relationship('technician', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }

    /**
     * Extract coordinates from various Google Maps link formats
     */
    private static function extractCoordinatesFromGoogleMapsLink(string $url): ?array
    {
        // Handle different Google Maps URL formats
        $patterns = [
            // Standard format: https://maps.google.com/?q=lat,lng
            '/[?&]q=(-?\d+\.?\d*),(-?\d+\.?\d*)/',
            // Place format: https://www.google.com/maps/place/.../@lat,lng,zoom
            '/@(-?\d+\.?\d*),(-?\d+\.?\d*),/',
            // Search format: https://www.google.com/maps/search/lat,lng
            '/search\/(-?\d+\.?\d*),(-?\d+\.?\d*)/',
            // Dir format: https://www.google.com/maps/dir//lat,lng
            '/dir\/\/(-?\d+\.?\d*),(-?\d+\.?\d*)/',
            // Simple lat,lng in URL
            '/(-?\d+\.?\d*),(-?\d+\.?\d*)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $lat = floatval($matches[1]);
                $lng = floatval($matches[2]);

                // Basic validation for reasonable coordinates
                if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                    return [
                        'lat' => $lat,
                        'lng' => $lng
                    ];
                }
            }
        }

        return null;
    }
}
