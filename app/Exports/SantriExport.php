<?php

namespace App\Imports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\ToModel;

class SantriImport implements ToModel
{
    public function model(array $row)
    {
        return new Santri([
            'nis' => Santri::generateNIS(), // otomatis
            'nama_lengkap' => $row[0],
            'jenis_kelamin' => $row[1],
            'alamat' => $row[2],
            'kelas' => $row[3],
            'angkatan' => $row[4],
            'status' => $row[5],
            'ustadz_pembimbing_id' => $row[6],
            'no_telp_wali' => $row[7],
        ]);
    }
}

