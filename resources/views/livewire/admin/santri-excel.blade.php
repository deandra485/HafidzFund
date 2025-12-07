<div class="min-h-screen bg-gray-50">

    {{-- Judul --}}
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

    {{-- Card Import --}}
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 transition hover:shadow-lg">

        <p class="text-gray-600 mt-1 leading-relaxed mb-6">
            Gunakan fitur <span class="font-semibold text-green-600">import Excel</span> untuk menambahkan data santri secara massal.
            Anda juga dapat melihat format file atau mengunduh template resmi melalui tombol di bawah.
        </p>

        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            ⚙️ Pengelolaan File Excel
        </h2>

        {{-- Input File + Import --}}
        <div class="flex flex-col md:flex-row items-center gap-4">
            <input type="file" wire:model="file"
                class="border border-gray-300 rounded-lg p-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:outline-none w-full md:w-80 shadow-sm">

            <button wire:click="import"
                class="flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-green-700 transition-all shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                Import Excel
            </button>
        </div>

        {{-- Tombol Formatting --}}
        <div class="flex flex-col md:flex-row items-center gap-4 mt-6">

            {{-- Link ke Google Spreadsheet --}}
            <a href="https://docs.google.com/spreadsheets/d/1jACSEcBCfr8hOy0Lu1cs1Ib4EcK5jiEl5PJAXuI7T1A/edit?gid=0"
                target="_blank"
                class="flex items-center gap-2 bg-yellow-500 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-yellow-600 transition-all shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6M9 8h6m-9 8h6m7 0a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v9z" />
                </svg>
                Lihat Format (Spreadsheet)
            </a>

            {{-- Download Template Excel --}}
            <a href="https://docs.google.com/spreadsheets/d/1jACSEcBCfr8hOy0Lu1cs1Ib4EcK5jiEl5PJAXuI7T1A/export?format=xlsx"
                class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 12v-2a2 2 0 012-2h12a2 2 0 012 2v2M7 16l5-5m0 0l5 5m-5-5v12" />
                </svg>
                Download Template Excel
            </a>

        </div>

        {{-- Catatan / Tata Cara --}}
        <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-5">
            <p class="text-gray-700 font-semibold mb-2">
                💡 Tata Cara Import Data Santri:
            </p>

            <ol class="text-gray-600 text-sm space-y-1.5 list-decimal ml-5">
                <li>Download template Excel atau buka format di Google Spreadsheet.</li>
                <li>Isi data sesuai kolom: <strong>Nama Lengkap, NIS, Kelas</strong>.</li>
                <li>Pastikan tidak ada kolom tambahan atau nama kolom yang berubah.</li>
                <li>Simpan file dalam format <strong>.xlsx</strong>.</li>
                <li>Upload file menggunakan tombol <strong>Choose File</strong>.</li>
                <li>Klik tombol <strong>Import Excel</strong> untuk memproses data.</li>
            </ol>

            <p class="text-xs text-gray-500 mt-3">
                Jika format kolom tidak sesuai, proses import akan gagal. Gunakan template resmi agar data kompatibel.
            </p>
        </div>

        {{-- Loader --}}
        <div wire:loading wire:target="import"
            class="mt-5 flex items-center gap-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg p-3">
            <svg class="animate-spin w-5 h-5 text-green-600"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="text-sm font-medium">Sedang memproses data, mohon tunggu...</span>
        </div>
    </div>
</div>
