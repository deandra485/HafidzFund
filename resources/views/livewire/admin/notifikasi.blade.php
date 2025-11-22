<div class="bg-white shadow rounded-xl border border-gray-200">

    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200 backdrop-blur-md bg-white/70 sticky top-0 z-50">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Notifikasi</h1>
            <p class="text-gray-500 mt-1">Kelola notifikasi pengguna dan sistem</p>
        </div>
    </header>

    @if($notifications->count() == 0)
        <div class="p-6 text-center text-gray-500">
            Tidak ada notifikasi.
        </div>
    @endif

    @foreach($notifications as $notif)
    <div class="p-5 border-b border-gray-100 hover:bg-gray-50 transition relative">

        <div class="flex justify-between items-start">
            <div>
                <p class="font-semibold text-gray-800">
                    {{ $notif->title }}
                </p>
                <p class="text-gray-500 text-xs mt-2">
                    {{ $notif->created_at->diffForHumans() }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if(!$notif->is_read)
                    <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">
                        Baru
                    </span>
                @endif

                <!-- Tombol Hapus -->
                <button
                    wire:click="deleteNotif({{ $notif->id }})"
                    class="text-red-500 hover:text-red-700 transition"
                    title="Hapus Notifikasi"
                >
                    <!-- Ikon Trash -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0115.916 21.75H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397M6.318 5.79a48.11 48.11 0 013.478-.397m0 0L9.96 3.75A1.5 1.5 0 0111.44 2.25h1.12a1.5 1.5 0 011.479 1.5l.163 1.643" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Jika tipe registrasi -->
        @if($notif->type === 'registrasi')
        <div class="mt-3 flex gap-3">
            <button
                class="bg-green-600 text-white px-3 py-1.5 text-xs rounded"
                wire:click="konfirmasiRegistrasi({{ $notif->related_id }}, {{ $notif->id }})">
                Konfirmasi
            </button>

            <button
                class="bg-red-600 text-white px-3 py-1.5 text-xs rounded"
                wire:click="tolakRegistrasi({{ $notif->related_id }}, {{ $notif->id }})">
                Tolak
            </button>
        </div>
        @endif

    </div>
    @endforeach

</div>
