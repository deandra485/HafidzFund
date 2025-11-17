<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.guest-user')]
class SantriBinaan extends Component
{
    public $selected_santri;

    public function addSantri()
    {
        $this->validate([
            'selected_santri' => 'required|exists:santri,id',
        ]);

        $santri = Santri::find($this->selected_santri);

        $santri->update([
            'ustadz_pembimbing_id' => Auth::id(),
        ]);

        session()->flash('message', 'Santri berhasil ditambahkan ke binaan Anda.');

        $this->reset('selected_santri');
    }

    public function removeSantri($id)
    {
        $santri = Santri::where('id', $id)
            ->where('ustadz_pembimbing_id', Auth::id())
            ->firstOrFail();

        $santri->update(['ustadz_pembimbing_id' => null]);
        session()->flash('message', 'Santri berhasil dihapus dari binaan Anda.');
    }

    public function render()
    {
        $ustadz = Auth::user();

        return view('livewire.ustadz.santri-binaan', [
            'santriList' => Santri::where('ustadz_pembimbing_id', $ustadz->id)->get(),
            'availableSantri' => Santri::whereNull('ustadz_pembimbing_id')->get(),
        ]);
    }
}
