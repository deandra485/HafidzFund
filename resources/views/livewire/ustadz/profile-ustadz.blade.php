<div class="min-h-screen bg-gray-50 mx-auto my-auto rounded-xl shadow">

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="min-h-screen bg-gray-50 mx-auto my-auto rounded-xl shadow">

        {{-- HEADER --}}
        <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200 backdrop-blur-md bg-white/70 sticky top-0 z-50">
        <div>
            <h2 class="text-2xl font-bold text-gray-700">Profil Saya</h2>
            <p class="text-gray-500">Kelola informasi akun Anda</p>
        </div>
        </header>

        {{-- CONTENT --}}

        <div class="p-6">

            {{-- FOTO + DISPLAY NAME --}}
            <div class="flex items-center gap-6 pb-6 border-b mb-6">

                {{-- Foto --}}
                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-green-200 bg-gray-200">
                    @if($foto)
                        <img src="{{ asset('storage/' . $foto) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-green-500 text-white text-4xl font-bold">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div>
                    <h3 class="text-2xl font-bold">{{ $name }}</h3>
                    <p class="text-gray-600">@ {{ $username }}</p>

                    <span class="mt-2 inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>

                {{-- Tombol Edit --}}
                <button wire:click="toggleEdit"
                        class="ml-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Edit Profil
                </button>

            </div>


            {{-- ===========================
                VIEW MODE
            ============================ --}}
            @if(!$isEditing)
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="text-sm text-gray-500">Nama</label>
                            <p class="font-medium">{{ $name }}</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">Username</label>
                            <p class="font-medium">{{ $username }}</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">Email</label>
                            <p class="font-medium">{{ $email }}</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">WhatsApp</label>
                            <p class="font-medium">{{ $whatsapp ?: '-' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-500">Nama Lengkap</label>
                            <p class="font-medium">{{ $nama_lengkap ?: '-' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-500">Alamat</label>
                            <p class="font-medium">{{ $alamat ?: '-' }}</p>
                        </div>

                    </div>
                </div>
            @endif


            {{-- ===========================
                EDIT MODE
            ============================ --}}
            @if($isEditing)

                <form wire:submit.prevent="updateProfile" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label>Nama</label>
                            <input type="text" wire:model="name" 
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label>Username</label>
                            <input type="text" wire:model="username" 
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                            @error('username') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label>Email</label>
                            <input type="email" wire:model="email" 
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label>WhatsApp</label>
                            <input type="text" wire:model="whatsapp" 
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                        </div>

                        <div class="md:col-span-2">
                            <label>Nama Lengkap</label>
                            <input type="text" wire:model="nama_lengkap" 
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                        </div>

                        <div class="md:col-span-2">
                            <label>Alamat</label>
                            <textarea rows="3" wire:model="alamat" 
                                      class="w-full mt-1 px-3 py-2 border rounded-lg"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label>Foto Profil</label>
                            <input type="file" wire:model="new_foto" 
                                   class="w-full border rounded-lg mt-1">
                            @error('new_foto') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

                            @if($new_foto)
                                <img src="{{ $new_foto->temporaryUrl() }}" class="w-24 h-24 mt-3 rounded-full object-cover">
                            @endif
                        </div>

                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="toggleEdit"
                                class="px-6 py-2 border rounded-lg">Batal</button>

                        <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            @endif


            {{-- ===========================
                PASSWORD SECTION
            ============================ --}}
            <div class="mt-10 border-t pt-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Keamanan</h3>

                    <button wire:click="togglePasswordChange"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Ubah Password
                    </button>
                </div>


                @if($isChangingPassword)

                    <form wire:submit.prevent="updatePassword" class="space-y-4">

                        <div>
                            <label>Password Saat Ini</label>
                            <input type="password" wire:model="current_password"
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                            @error('current_password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label>Password Baru</label>
                            <input type="password" wire:model="new_password"
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" wire:model="new_password_confirmation"
                                   class="w-full mt-1 px-3 py-2 border rounded-lg">
                            @error('new_password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="togglePasswordChange"
                                    class="px-6 py-2 border rounded-lg">Batal</button>

                            <button type="submit"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Update Password
                            </button>
                        </div>

                    </form>

                @endif

            </div>

        </div>
    </div>

</div>
