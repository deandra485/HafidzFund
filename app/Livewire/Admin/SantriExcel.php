<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Exports\SantriExport;
use App\Imports\SantriImport;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.guest')]
class SantriExcel extends Component
{
    use WithFileUploads;

    public $file;

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new SantriImport, $this->file->getRealPath());

        session()->flash('success', '✅ Data santri berhasil diimport!');
    }

    // public function export()
    // {
    //     return response()->streamDownload(function () {
    //         echo Excel::raw(new SantriExport, \Maatwebsite\Excel\Excel::XLSX);
    //     }, 'data-santri.xlsx');
    // }

    public function render()
    {
        return view('livewire.admin.santri-excel');
    }
}
