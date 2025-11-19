<div class="min-h-screen bg-gray-50 mx-auto my-auto rounded-xl shadow">
    <!-- Header -->
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200 backdrop-blur-md bg-white/70 sticky top-0 z-50">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Notifikasi</h1>
            <p class="text-gray-500 mt-1">Kelola notifikasi dan pemberitahuan penting</p>
        </div>
    </header>

    <div class="bg-white shadow rounded-xl border border-gray-200">

        @if($notifications->count() == 0)
            <div class="p-6 text-center text-gray-500">
                Tidak ada notifikasi.
            </div>
        @endif

        @foreach($notifications as $notif)
        <div class="p-5 border-b border-gray-100 hover:bg-gray-50 transition">

            <div class="flex justify-between">
                <p class="font-semibold text-gray-800">
                    {{ $notif->title }}
                </p>

                @if(!$notif->is_read)
                    <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">
                        Baru
                    </span>
                @endif
            </div>

            <!-- Khusus notifikasi registrasi -->
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

            <p class="text-gray-500 text-xs mt-2">
                {{ $notif->created_at->diffForHumans() }}
            </p>

        </div>
        @endforeach

    </div>

</div>
