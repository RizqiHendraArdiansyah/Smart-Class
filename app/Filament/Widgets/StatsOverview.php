<?php

namespace App\Filament\Widgets;

use App\Models\Kelas\KelasModel;
use App\Models\Materi\MateriModel;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Dosen', User::role('dosen')->count())
                ->description('Dosen aktif')
                ->icon('heroicon-o-users'),

            Stat::make('Total Kelas', KelasModel::where('is_aktif', true)->count())
                ->description('Kelas aktif')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Total Mahasiswa', User::role('mahasiswa')->count())
                ->description('Mahasiswa aktif')
                ->icon('heroicon-o-user-group'),

            Stat::make('Total Materi', MateriModel::count())
                ->description('Materi tersedia')
                ->icon('heroicon-o-book-open'),
        ];
    }
}
