<?php

namespace App\Filament\Mahasiswa\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();

        return [
            // Stat::make('Kelas Aktif', '4')
            //     ->description('Semester ini')
            //     ->icon('heroicon-o-academic-cap'),

            // Stat::make('Tugas Menunggu', '3')
            //     ->description('Perlu diselesaikan')
            //     ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Materi Baru', '7')
                ->description('Belum dibaca')
                ->icon('heroicon-o-book-open'),

            // Stat::make('Diskusi Aktif', '12')
            //     ->description('Pesan baru')
            //     ->icon('heroicon-o-chat-bubble-left-right'),
        ];
    }
}
