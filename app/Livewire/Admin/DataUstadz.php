<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.guest')]
class DataUstadz extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $perPage = 10;

    // Form properties
    public $modalOpen = false;
    public $modalMode = 'add';
    public $userId;
    
    public $username;
    public $password;
    public $password_confirmation;
    public $role = ['ustadz', 'admin'];
    public $nama_lengkap;
    public $email;
    public $no_telp;
    public $is_active = true;

    protected $queryString = ['search', 'filterStatus'];

    protected function rules()
    {
        $rules = [
            'nama_lengkap' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'no_telp' => 'nullable|max:20',
            'role' => 'required|in:admin,ustadz',
            'is_active' => 'boolean',
        ];

        if ($this->modalMode === 'add') {
            $rules['username'] = 'required|min:5|max:50|unique:users,username|alpha_dash';
            $rules['password'] = 'required|min:6|confirmed';
        } else {
            $rules['username'] = 'required|min:5|max:50|unique:users,username,' . $this->userId . '|alpha_dash';
            $rules['password'] = 'nullable|min:6|confirmed';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($mode = 'add', $id = null)
    {
        $this->modalMode = $mode;
        $this->modalOpen = true;
        $this->resetValidation();

        if ($mode === 'edit' && $id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->username = $user->username;
            $this->role = $user->role;
            $this->nama_lengkap = $user->nama_lengkap;
            $this->email = $user->email;
            $this->no_telp = $user->no_telp;
            $this->is_active = $user->is_active;
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset([
            'userId', 'username', 'password', 'password_confirmation',
            'role', 'nama_lengkap', 'email', 'no_telp', 'is_active'
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->modalMode === 'add') {
                $user = User::create([
                    'username' => $this->username,
                    'password' => Hash::make($this->password),
                    'role' => $this->role,
                    'nama_lengkap' => $this->nama_lengkap,
                    'email' => $this->email,
                    'no_telp' => $this->no_telp,
                    'is_active' => $this->is_active,
                ]);

                LogAktivitas::log('Menambah ' . $this->role . ' baru: ' . $this->nama_lengkap, 'User');
                session()->flash('message', 'Data ' . $this->role . ' berhasil ditambahkan!');
            } else {
                $user = User::findOrFail($this->userId);
                
                $data = [
                    'username' => $this->username,
                    'role' => $this->role,
                    'nama_lengkap' => $this->nama_lengkap,
                    'email' => $this->email,
                    'no_telp' => $this->no_telp,
                    'is_active' => $this->is_active,
                ];

                if ($this->password) {
                    $data['password'] = Hash::make($this->password);
                }

                $user->update($data);

                LogAktivitas::log('Mengupdate data ' . $this->role . ': ' . $this->nama_lengkap, 'User');
                session()->flash('message', 'Data ' . $this->role . ' berhasil diupdate!');
            }

            $this->closeModal();
            $this->dispatch('refresh-table');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = !$user->is_active;
            $user->save();

            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            LogAktivitas::log('Status user ' . $user->nama_lengkap . ' ' . $status, 'User');

            session()->flash('message', 'Status berhasil diubah!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Check if user has santri binaan
            if ($user->santriBinaan()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus ustadz yang masih memiliki santri binaan!');
                return;
            }

            $nama = $user->nama_lengkap;
            $user->delete();

            LogAktivitas::log('Menghapus user: ' . $nama, 'User');
            session()->flash('message', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function resetPassword($id)
    {
        try {
            $user = User::findOrFail($id);
            $newPassword = 'santri123'; // Default password
            $user->password = Hash::make($newPassword);
            $user->save();

            LogAktivitas::log('Reset password user: ' . $user->nama_lengkap, 'User');
            session()->flash('message', 'Password berhasil direset menjadi: ' . $newPassword);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $ustadzQuery = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus !== '', function ($q) {
                $q->where('is_active', $this->filterStatus);
            })
            ->latest();

        $ustadzList = $ustadzQuery->paginate($this->perPage);

        // Add statistics
        foreach ($ustadzList as $ustadz) {
            $ustadz->total_santri = Santri::where('ustadz_pembimbing_id', $ustadz->id)->count();
            $ustadz->total_setoran = SetoranHafalan::where('ustadz_id', $ustadz->id)
                ->whereMonth('tanggal_setoran', now()->month)
                ->count();
        }

        $statistik = [
            'total_ustadz' => User::ustadz()->count(),
            'ustadz_aktif' => User::ustadz()->active()->count(),
            'total_admin' => User::admin()->count(),
            'pending_activation' => User::where('is_active', false)->count(),
        ];

        return view('livewire.admin.data-ustadz', [
            'ustadzList' => $ustadzList,
            'statistik' => $statistik,
        ]);
    }
}
