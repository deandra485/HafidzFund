<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-300 to-white-100">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('enno/assets/img/logo2.png') }}" alt="HafidzFund Logo" class="mx-auto w-20 h-20 mb-3">
            <h1 class="text-3xl font-bold text-gray-800">HafidzFund</h1>
            <p class="text-gray-600 mt-2">Sistem Manajemen Hafalan & Keuangan</p>
        </div>

        <!-- Form Login -->
        <form wire:submit="login">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" wire:model="username" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="Masukkan username">
                @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" wire:model="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="Masukkan password">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="remember" class="rounded border-gray-300">
                    <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-orange-600 text-white py-3 rounded-lg font-semibold hover:bg-orange-700 transition">
                Login
            </button>
        </form>

        <!-- Link ke Register -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a wire:navigate href="{{ route('auth.register') }}" class="text-purple-600 hover:underline font-medium">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </div>
</div>
