<?php

namespace App\Http\Controllers;

use App\Exports\DosenExport;
use App\Exports\MahasiswaExport;
use App\Exports\SoalExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportImportController extends Controller
{
    public function exportDosen()
    {
        return Excel::download(new DosenExport(), 'dosen.xlsx');
    }
    public function exportSoal()
    {
        return Excel::download(new SoalExport(), 'soal.xlsx');
    }

    public function exportMahasiswa()
    {
        return Excel::download(new MahasiswaExport(), 'mahasiswa.xlsx');
    }
}
