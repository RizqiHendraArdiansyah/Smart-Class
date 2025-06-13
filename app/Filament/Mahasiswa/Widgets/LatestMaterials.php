<?php

namespace App\Filament\Mahasiswa\Widgets;

use App\Models\Materi\MateriModel;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestMaterials extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Materi Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MateriModel::query()
                    ->latest()
                    ->limit(3)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul'),
                TextColumn::make('module.kelas.name')
                    ->label('Kelas'),
                TextColumn::make('module.kelas.user.name')
                    ->label('Dosen'),
                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d F Y'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->state('Baru')
                    ->color('success'),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('lihat')
                    ->label('Lihat Materi')
                    ->url(fn ($record) => '#')
                    ->button(),
            ]);
    }
}
