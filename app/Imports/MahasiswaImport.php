<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Akses data dengan nama kolom
        $user = User::where('email', $row['email'])
            ->orWhere('nomor_induk', $row['nomor_induk'])
            ->first();

        if ($user) {
            // Update data
            $user->name = $row['nama'];
            $user->email = $row['email'];
            $user->nomor_induk = $row['nomor_induk'];
            $user->telepon = $row['telepon'];
            $user->save();
            $user->assignRole('mahasiswa');
            return $user;
        } else {
            // Buat baru
            $user = new User([
                'name' => $row['nama'],
                'email' => $row['email'],
                'nomor_induk' => $row['nomor_induk'],
                'telepon' => $row['telepon'],
                'password' => Hash::make('12345678'),
            ]);
            $user->save();
            $user->assignRole('mahasiswa');
            return $user;
        }
    }
}
