<div class="min-h-screen bg-gray-50">
    {{-- Judul dan Deskripsi --}}
    <div class="border-b pb-6 mb-8 bg-white shadow-sm rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
            📊 Import Data Santri
        </h1>
    </div>

    {{-- Notifikasi --}}
    @if (session()->has('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Card Import & Export --}}
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 transition hover:shadow-lg">
         <p class="text-gray-600 mt-3 leading-relaxed max-w-3xl">
            Halaman ini dirancang untuk mempermudah pengelolaan <span class="font-semibold text-green-600">data santri</span> 
            melalui fitur <span class="font-semibold text-green-600">import</span> dan 
            <span class="font-semibold text-blue-600">export</span> berbasis file Excel.  
            Anda dapat menambahkan ratusan data sekaligus hanya dengan satu kali unggah file.  
            Selain itu, fitur <span class="text-blue-600 font-medium">export</span> memungkinkan Anda menyimpan atau mencadangkan data santri dalam bentuk Excel untuk keperluan laporan, audit, atau migrasi sistem dengan cepat dan aman.
        </p>
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            ⚙️ Pengelolaan File Excel
        </h2>
        <div class="flex flex-col md:flex-row items-center gap-4">
            {{-- Input file dan tombol import --}}
            <div class="flex items-center gap-3 w-full md:w-auto">
                <input type="file" wire:model="file"
                    class="border border-gray-300 rounded-lg p-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:outline-none w-full md:w-80 shadow-sm transition">
                <button wire:click="import"
                    class="flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-green-700 transition-all shadow-sm hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                    </svg>
                    Import Excel
                </button>
            </div>

            {{-- Tombol export --}}
            {{-- <button wire:click="export"
                class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 12v-2a2 2 0 012-2h12a2 2 0 012 2v2M7 16l5-5m0 0l5 5m-5-5v12" />
                </svg>
                Export Excel
            </button> --}}
        </div>
         <div class="mt-8 bg-white border border-gray-100 rounded-lg shadow-sm p-5">
            <p class="text-gray-500 text-sm leading-relaxed">
                💡 <span class="font-semibold text-gray-700">Catatan:</span> Pastikan format file Excel yang akan Anda unggah memiliki struktur kolom yang sesuai dengan sistem, 
                yaitu <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">Nama Lengkap, NIS, Kelas</code>.  
                File dengan format tidak sesuai dapat menyebabkan kegagalan proses import.  
                Jika Anda ragu, silakan unduh terlebih dahulu contoh file template dari sistem untuk memastikan kompatibilitas data Anda.
            </p>
        </div>

        {{-- Loader --}}
        <div wire:loading wire:target="import,export"
            class="mt-6 flex items-center gap-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg p-3">
            <svg class="animate-spin w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="text-sm font-medium">Sedang memproses data, mohon tunggu sebentar...</span>
        </div>
    </div>

    {{-- Catatan kecil --}}
    
</div>
