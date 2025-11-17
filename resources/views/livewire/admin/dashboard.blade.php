@section('title', 'Dashboard')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body>
        <!-- Sidebar -->
        <!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <!-- HEADER -->
    <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
            <p class="text-sm text-gray-500">Selamat datang kembali, Admin!</p>
        </div>
        </div>
    </header>

        <!-- STAT CARDS -->
        <main style="flex: 1; overflow-y: auto; padding: 1.5rem; background-color: #f9fafb;">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">


            <!-- Card 1 -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-700 p-6 rounded-xl text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">+12%</span>
                </div>
                <h3 class="text-3xl font-bold">{{ $totalSantri }}</h3>
                <p class="text-sm opacity-80">Total Santri Aktif</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-gradient-to-br from-green-500 to-green-700 p-6 rounded-xl text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">+8%</span>
                </div>
                <h3 class="text-3xl font-bold">{{ $totalHafal30Juz }}</h3>
                <p class="text-sm opacity-80">Hafal 30 Juz</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-6 rounded-xl text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Bulan Ini</span>
                </div>
                <h3 class="text-3xl font-bold">{{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
                <p class="text-sm opacity-80">Uang Santri</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-700 p-6 rounded-xl text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Hari Ini</span>
                </div>
                <h3 class="text-3xl font-bold">{{ $setoranHariIni }}</h3>
                <p class="text-sm opacity-80">Setoran Hafalan</p>
            </div>

        </div>
        </main>

        <!-- CHARTS -->
    <div>

    <!-- FILTER + CHART WRAPPER -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <!-- Hafalan Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold">Progress Hafalan Per Kelas</h3>

                <select wire:model="hafalanRange" class="text-sm border rounded-lg px-3 py-1">
                    <option>Bulan Ini</option>
                    <option>3 Bulan</option>
                    <option>6 Bulan</option>
                </select>
            </div>

            <canvas id="hafalanChart"></canvas>
        </div>

        <!-- Keuangan Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold">Statistik Keuangan</h3>

                <select wire:model="keuanganYear" class="text-sm border rounded-lg px-3 py-1">
                    <option>2025</option>
                    <option>2024</option>
                </select>
            </div>

            <canvas id="keuanganChart"></canvas>
        </div>

    </div>

    <!-- =================== -->
    <!-- CHART JS -->
    <!-- =================== -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("livewire:initialized", () => {

            // =======================
            // 1. INITIAL CHARTS DATA
            // =======================
            const initialHafalan = @js($this->getHafalanData());
            const initialKeuangan = @js($this->getKeuanganData());

            // =======================
            // 2. CREATE HAFALAN CHART
            // =======================
            const ctxHafalan = document.getElementById('hafalanChart').getContext('2d');
            window.hafalanChart = new Chart(ctxHafalan, {
                type: 'bar',
                data: {
                    labels: initialHafalan.labels,
                    datasets: [{
                        label: 'Rata-rata Juz',
                        data: initialHafalan.data,
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderRadius: 8,
                    }]
                },
                options: { responsive: true }
            });

            // =======================
            // 3. CREATE KEUANGAN CHART
            // =======================
            const ctxKeuangan = document.getElementById('keuanganChart').getContext('2d');

            window.keuanganChart = new Chart(ctxKeuangan, {
                type: 'line',
                data: {
                    labels: initialKeuangan.labels,
                    datasets: [{
                        label: "Keuangan",
                        data: initialKeuangan.data,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: { responsive: true }
            });

            // =====================================================
            // 4. LISTEN LIVEWIRE EVENTS → UPDATE CHARTS DINAMIS
            // =====================================================
            Livewire.on("updateHafalanChart", (payload) => {
                window.hafalanChart.data.labels = payload.labels;
                window.hafalanChart.data.datasets[0].data = payload.data;
                window.hafalanChart.update();
            });

            Livewire.on("updateKeuanganChart", (payload) => {
                window.keuanganChart.data.labels = payload.labels;
                window.keuanganChart.data.datasets[0].data = payload.data;
                window.keuanganChart.update();
            });

        });
    </script>

</body>
</html>