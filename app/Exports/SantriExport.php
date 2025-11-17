<?php

namespace App\Imports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\ToModel;

class SantriImport implements ToModel
{
    public function model(array $row)
    {
        return new Santri([
            'nama_lengkap' => $row[0],
            'nis'          => $row[1],
            'kelas'        => $row[2],
        ]);
    }
}
