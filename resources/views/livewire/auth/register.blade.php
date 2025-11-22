<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-300 to-white-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-2xl">
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div>
                    <img src="{{ asset('enno/assets/img/logo2.png') }}" alt="HafidzFund Logo" class="mx-auto w-20 h-20 mb-3">
                    </img>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Registrasi HafidzFund</h1>
            <p class="text-gray-600 mt-2">Daftar sebagai Ustadz atau Ustadzah</p>
        </div>

        @if (session()->has('message'))
            <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="register">
            <div class="grid grid-cols-2 gap-4">
                <!-- Username -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                    <input type="text" wire:model="username" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="username_anda">
                    @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" wire:model="nama_lengkap"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Nama lengkap Anda">
                    @error('nama_lengkap') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" wire:model="email"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="email@example.com">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- No Telp -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="tel" wire:model="no_telp"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="08xxxxxxxxxx">
                    @error('no_telp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Role -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Sebagai *</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" wire:model="role" value="ustadz" class="peer sr-only" checked>
                           <div class="w-[600px] border-2 border-gray-300 rounded-lg p-4 text-center peer-checked:border-orange-500 peer-checked:bg-orange-50">
                            <svg class="w-8 h-8 mx-auto mb-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="font-semibold">Ustadz atau Ustadzah</p>
                        </div>
                        </label>
                    </div>
                    @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" wire:model="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Min. 6 karakter">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password *</label>
                    <input type="password" wire:model="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Ulangi password">
                </div>
            </div>

            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">Perhatian!</p>
                        <p class="text-sm text-yellow-700">Untuk Username, gunakan format: ustadz_nama atau ustadzah_nama</p>
                        <p class="text-sm text-yellow-700">Untuk Login Tunggu konfirmasi dari admin</p>
                    </div>
                </div>
            </div>

            <div class="w-full mt-5">
                <a href="{{ route('auth.login') }}"
                wire:navigate
                class="block w-full bg-orange-600 text-white py-3 rounded-lg font-semibold
                        hover:bg-orange-700 transition duration-300 text-center">
                    Login
                </a>
            </div>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('auth.login') }}" wire:navigate class="text-orange-600 hover:text-orange-700 font-semibold">Login di sini</a>
                </p>
            </div>
        </form>
    </div>
</div>