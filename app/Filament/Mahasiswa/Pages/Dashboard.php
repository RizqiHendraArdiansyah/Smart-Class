<?php

namespace App\Filament\Mahasiswa\Pages;

use Filament\Pages\Dashboard as BasePage;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi\MateriModel;
use Filament\Pages\Page;
use Carbon\Carbon;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?string $slug = 'dashboard';

    protected static string $view = 'filament.mahasiswa.pages.dashboard';

    public $materiList;

    public function mount(): void
    {
        $user = Auth::user();
        $classIds = $user->kelas->pluck('id');

        $this->materiList = MateriModel::with(['module.kelas', 'kuis'])
            ->whereHas('module', function($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTitle(): string
    {
        return 'Selamat Datang, '.Auth::user()->name;
    }

    public function getSubheading(): string
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        // Semester Genap: Februari - Juli (2-7)
        // Semester Ganjil: Agustus - Januari (8-1)
        $semester = ($month >= 2 && $month <= 7) ? 'Genap' : 'Ganjil';

        // Tahun Ajaran: Jika semester genap, tahun ajaran = tahun-1/tahun
        // Jika semester ganjil, tahun ajaran = tahun/tahun+1
        $academicYear = $semester === 'Genap'
            ? ($year - 1) . '/' . $year
            : $year . '/' . ($year + 1);

        return "Semester {$semester} {$academicYear}";
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         \App\Filament\Mahasiswa\Widgets\StatsOverview::class,
    //     ];
    // }

    // protected function getFooterWidgets(): array
    // {
    //     return [
    //         \App\Filament\Mahasiswa\Widgets\LatestMaterials::class,
    //         \App\Filament\Mahasiswa\Widgets\TodaySchedule::class,
    //     ];
    // }
}
