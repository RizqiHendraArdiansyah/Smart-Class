<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DosenExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'dosen');
        })
            ->select('name', 'email', 'nomor_induk', 'telepon')
            ->get();
    }

    public function headings(): array
    {
        return [
            'nama',
            'email',
            'nomor_induk',
            'telepon',
        ];
    }
}
