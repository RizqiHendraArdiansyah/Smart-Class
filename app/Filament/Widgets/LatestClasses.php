<?php

namespace App\Filament\Widgets;

use App\Models\Kelas\KelasModel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestClasses extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Kelas Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                KelasModel::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Kelas'),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Jumlah Mahasiswa'),
                TextColumn::make('batch_year')
                    ->label('Angkatan'),
                TextColumn::make('semester')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Ganjil',
                        2 => 'Genap',
                        default => ''
                    }),
            ]);
    }
}
