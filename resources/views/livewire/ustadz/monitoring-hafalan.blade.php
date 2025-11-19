<div class="flex flex-col h-full overflow-hidden">

    <!-- Header -->
    <header class="backdrop-blur-md bg-white/80 shadow border-b border-green-100 sticky top-0 z-50">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Monitoring Hafalan Santri</h1>
                <p class="text-gray-500 mt-1">Monitor progres hafalan santri secara real-time</p>
            </div>
        </div>
    </header>

    <!-- Filter Card -->
    <div class="bg-white shadow rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-semibold">Periode</label>
                <select class="mt-1 w-full border rounded-lg p-2" wire:model.live="periode">
                    <option value="minggu">Minggu Ini</option>
                    <option value="bulan">Bulan Ini</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Filter Santri</label>
                <select class="mt-1 w-full border rounded-lg p-2" wire:model.live="filterSantri">
                    <option value="">Semua Santri</option>
                    @foreach($santriList as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button wire:click="applyFilter" class="w-full bg-green-600 text-white p-3 rounded-lg shadow hover:bg-green-700 transition">
                     Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-blue-500 relative overflow-hidden">
            <h3 class="text-gray-500 text-sm">Total Setoran</h3>
            <p class="text-3xl font-bold">{{ $stat['total'] }}</p>
            <p class="text-green-600 text-xs mt-1 flex items-center">⬆ 12% dari minggu lalu</p>
            <div class="absolute right-2 top-2 text-blue-300 text-6xl opacity-20">📘</div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-green-500 relative overflow-hidden">
            <h3 class="text-gray-500 text-sm">Diterima</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stat['lancar'] }}</p>
            <p class="text-xs text-gray-400">Penilaian lancar</p>
            <div class="absolute right-2 top-2 text-green-300 text-6xl opacity-20">✔️</div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-red-500 relative overflow-hidden">
            <h3 class="text-gray-500 text-sm">Kurang Lancar</h3>
            <p class="text-3xl font-bold text-red-600">{{ $stat['kurang_lancar'] }}</p>
            <p class="text-xs text-gray-400">Perlu bimbingan</p>
            <div class="absolute right-2 top-2 text-red-300 text-6xl opacity-20">❌</div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-yellow-500 relative overflow-hidden">
            <h3 class="text-gray-500 text-sm">Terbata</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $stat['terbata'] }}</p>
            <p class="text-xs text-gray-400">Butuh latihan</p>
            <div class="absolute right-2 top-2 text-yellow-300 text-6xl opacity-20">⏳</div>
        </div>

    </div>

    <!-- Grafik -->
    <div class="bg-white p-4 rounded-xl shadow mb-10">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <h3 class="text-lg font-semibold"> Grafik Setoran Hafalan</h3>
        <p class="text-sm text-gray-500 mb-4">Periode: {{ $periodeLabel }}</p>
        <canvas id="chartSetoran" class="w-full" height="120"></canvas>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    let barChart = null;

    Livewire.on('updateChart', (labels, data) => {
        if (barChart) barChart.destroy();

        const ctx = document.getElementById('chartSetoran').getContext('2d');
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'line',
                        label: "Trend Setoran",
                        data: data,
                        borderColor: 'rgba(99,102,241,1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: false,
                    },
                    {
                        type: 'bar',
                        label: "Jumlah Setoran",
                        data: data,
                        backgroundColor: 'rgba(59,130,246,0.5)',
                        borderColor: 'rgba(59,130,246,1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    });
});
</script>

</body>
</html>
