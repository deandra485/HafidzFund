<?php

namespace App\Exports;

use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TugasExport implements FromView
{
    public function view(): View
    {
        $data = array(
            'tugas' => Tugas::with('user')->get(),
            'tanggal' => now()->format('d-m-Y'),
            'jam' => now()->format('H:i:s'),
        );
        return view('admin/tugas/excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        // wrap text untuk kolom D (Tugas)
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);

        // biar tinggi row otomatis
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // auto size untuk semua kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // bold header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        return [];
    }
}
