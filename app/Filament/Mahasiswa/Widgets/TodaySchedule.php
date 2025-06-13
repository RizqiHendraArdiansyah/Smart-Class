<?php

namespace App\Filament\Mahasiswa\Widgets;

use App\Models\Kelas\KelasModel;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodaySchedule extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Jadwal Hari Ini';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                KelasModel::query()
                    ->where('is_aktif', true)
                    // ->orderBy('jadwal_mulai')
                    ->limit(3)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Kelas'),
                TextColumn::make('user.name')
                    ->label('Dosen'),
                TextColumn::make('ruangan')
                    ->label('Ruangan'),
                // TextColumn::make('jadwal_mulai')
                //     ->label('Waktu')
                //     ->formatStateUsing(fn ($state) => date('H:i', strtotime($state)) . ' - ' . date('H:i', strtotime('+2 hours', strtotime($state)))),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->state('Sedang Berlangsung')
                    ->color('success'),
            ]);
    }
}
