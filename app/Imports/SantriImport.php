<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\ProgressHafalan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SantriImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buat data santri
        $santri = Santri::create([
            'nis' => Santri::generateNIS(),
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => strtoupper(trim($row['jenis_kelamin'])),
            'alamat' => $row['alamat'],
            'kelas' => $row['kelas'],
            'angkatan' => $row['angkatan'],
            'status' => strtolower(trim($row['status'])),
            'ustadz_pembimbing_id' => $row['ustadz_pembimbing_id'],
            'no_telp_wali' => $row['no_telp_wali'],
        ]);

        return $santri;
    }
}
