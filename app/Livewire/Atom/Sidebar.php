<?php

namespace App\Livewire\Atom;

use Livewire\Component;

class Sidebar extends Component
{
    public $active = 'dashboard'; // default menu aktif

    public function setActive($menu)
    {
        $this->active = $menu;
    }

    public function render()
    {
        return view('livewire.atom.sidebar');
    }
}
