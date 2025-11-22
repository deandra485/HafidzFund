<div class="flex: 1; display: flex; flex-direction: column; overflow: hidden;">

    <!-- Header -->
    <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Monitoring Hafalan Santri</h1>
                <p class="text-gray-500 mt-1">Kelola dan pantau monitoring hafalan santri secara real-time</p>
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

    <!-- ================= BAR CHART ================= -->
    <div class="bg-white p-4 rounded-xl shadow mb-10">
        <h3 class="text-lg font-semibold">Grafik Setoran Hafalan</h3>
        <p class="text-sm text-gray-500 mb-4">Periode: {{ $periodeLabel }}</p>

    <div class="h-64">  <!-- GRAFIK TIDAK AKAN GEDE LAGI -->
        <canvas id="chartSetoran"></canvas>
    </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:initialized', () => {

    let chart = null;

    Livewire.on('updateChart', ({ labels, data }) => {

        const canvas = document.getElementById('chartSetoran');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        if (chart) chart.destroy();

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, "rgba(59,130,246,0.8)");
        gradient.addColorStop(1, "rgba(56,189,248,0.4)");

        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: "Nilai Setoran",
                    data: data,
                    backgroundColor: gradient,
                    borderColor: "rgba(59,130,246,1)",
                    borderWidth: 2,
                    borderRadius: 12,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...data) + 6,
                        ticks: {
                            precision: 0,    // ⬅️ angka jadi 1,2,3,4 (tanpa koma)
                            stepSize: 1,     // ⬅️ biar integer
                            color: "#6b7280",
                            font: { size: 12 }
                        },
                        grid: {
                            color: "rgba(203,213,225,0.4)",
                            borderDash: [4,4]
                        }
                    },
                    x: {
                        ticks: {
                            color: "#374151",
                            font: { size: 12, weight: "600" }
                        },
                        grid: { display: false }
                    }
                }
            }
        });

    });
});
</script>

</body>
</html>
