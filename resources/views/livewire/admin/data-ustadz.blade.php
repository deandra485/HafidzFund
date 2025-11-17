<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200 backdrop-blur-md bg-white/70 sticky top-0 z-50">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Ustadz & Admin</h1>
            <p class="text-gray-500 mt-1">Kelola data ustadz dan admin sistem</p>
        </div>

        <div class="flex gap-3">
            {{-- <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-2 bg-gray-100 border border-gray-200 px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-200 transition">
                ← Kembali
            </a> --}}

            <button wire:click="openModal('add')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah User
            </button>
        </div>
    </header>

    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-10">
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <p class="text-green-100 text-sm mb-2">Total Ustadz</p>
            <h3 class="text-3xl font-bold">{{ $statistik['total_ustadz'] }}</h3>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-blue-100 text-sm mb-2">Ustadz Aktif</p>
            <h3 class="text-3xl font-bold">{{ $statistik['ustadz_aktif'] }}</h3>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-purple-100 text-sm mb-2">Total Admin</p>
            <h3 class="text-3xl font-bold">{{ $statistik['total_admin'] }}</h3>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-orange-100 text-sm mb-2">Pending Aktivasi</p>
            <h3 class="text-3xl font-bold">{{ $statistik['pending_activation'] }}</h3>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari User</label>
                <div class="relative">
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Nama, username, atau email..." class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-green-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="filterStatus" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>

    <!-- User Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ustadzList as $user)
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100">
            <!-- Card Header with Role Badge -->
            <div class="relative h-24 {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-500 to-purple-700' : 'bg-gradient-to-br from-green-500 to-green-700' }}">
                <div class="absolute -bottom-10 left-6">
                    <div class="w-20 h-20 bg-white rounded-full border-4 border-white shadow-lg flex items-center justify-center text-2xl font-bold {{ $user->role === 'admin' ? 'text-purple-600' : 'text-green-600' }}">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                </div>
                <div class="absolute top-4 right-4">
                    @if($user->is_active)
                        <span class="px-3 py-1 text-xs font-semibold text-white bg-white bg-opacity-20 rounded-full">Aktif</span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Tidak Aktif</span>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="pt-14 px-6 pb-6">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500"><?php echo e('@'.$user->username); ?></p>
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'text-purple-800 bg-purple-100' : 'text-green-800 bg-green-100' }} rounded-full">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $user->email }}
                    </div>
                    @if($user->whatsapp)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $user->whatsapp }}
                    </div>
                    @endif
                </div>

                @if($user->role === 'ustadz')
                <div class="grid grid-cols-2 gap-3 mb-4 pt-4 border-t">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $user->total_santri }}</p>
                        <p class="text-xs text-gray-500">Santri Binaan</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $user->total_setoran }}</p>
                        <p class="text-xs text-gray-500">Setoran Bulan Ini</p>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-2">
                    <button wire:click="toggleStatus({{ $user->id }})" 
                            class="flex-1 px-3 py-2 text-sm {{ $user->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg font-medium transition"
                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                        {{ $user->is_active ? '❌ Nonaktifkan' : '✅ Aktifkan' }}
                    </button>
                    
                    <button wire:click="openModal('edit', {{ $user->id }})" 
                            class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition"
                            title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>

                    <button wire:click="resetPassword({{ $user->id }})" 
                            wire:confirm="Reset password user ini menjadi 'santri123'?"
                            class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition"
                            title="Reset Password">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </button>

                    <button wire:click="delete({{ $user->id }})" 
                            wire:confirm="Yakin ingin menghapus user ini?"
                            class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition"
                            title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-gray-500 text-lg">Tidak ada data ditemukan</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $ustadzList->links() }}
    </div>

    <!-- Modal Form Add/Edit -->
    @if($modalOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b flex items-center justify-between z-10">
                <h3 class="text-2xl font-bold text-gray-800">
                    {{ $modalMode === 'add' ? 'Tambah User Baru' : 'Edit Data User' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="save" class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="username" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="role" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                            <option value="ustadz">Ustadz</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="nama_lengkap" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        @error('nama_lengkap') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- No Telp -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                        <input type="tel" wire:model="no_telp" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @error('no_telp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password {{ $modalMode === 'edit' ? '(Kosongkan jika tidak diubah)' : '' }} <span class="text-red-500">{{ $modalMode === 'add' ? '*' : '' }}</span>
                        </label>
                        <input type="password" wire:model="password" class="w-full border border-gray-300 rounded-lg px-4 py-2" {{ $modalMode === 'add' ? 'required' : '' }}>
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    <!-- Status -->
                    <div class="col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="w-5 h-5 text-green-600 rounded">
                            <span class="text-sm font-medium text-gray-700">Aktifkan akun sekarang</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 mt-6 pt-6 border-t">
                    <button type="button" wire:click="closeModal" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition shadow-lg">
                        {{ $modalMode === 'add' ? 'Tambah User' : 'Update User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>