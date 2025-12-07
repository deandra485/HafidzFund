<div class="flex: 1; display: flex; flex-direction: column; overflow: hidden;">

    <!-- Header -->
     <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
        <div class="px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight"> Kehadiran Santri</h1>
                    <p class="text-gray-500 mt-1">Kelola dan pantau kehadiran santri multi-sesi secara real-time</p>
                </div>
                
                <!-- Date Picker & View Mode Toggle -->
                <div class="flex gap-3 items-center">
                    <input type="date" 
                        wire:model.live="tanggal"
                        class="border border-gray-300 px-4 py-2.5 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 bg-white">
                    
                    <div class="flex bg-white rounded-xl shadow-sm border border-gray-200 p-1">
                        <button wire:click="$set('viewMode', 'sesi')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $viewMode === 'sesi' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            Per Sesi
                        </button>
                        <button wire:click="$set('viewMode', 'harian')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $viewMode === 'harian' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            Harian
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="flex flex-col sm:flex-row gap-3 mt-4">
                <input type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="🔍 Cari nama santri..."
                    class="flex-1 border border-gray-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500">
                
                <select wire:model.live="filterKelas"
                    class="border border-gray-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Semua Kelas</option>
                    <option value="1A">7</option>
                    <option value="1B">8</option>
                    <option value="2A">9</option>
                    <option value="2B">10</option>
                    <option value="2B">11</option>
                    <option value="2B">12</option>
                </select>

                <button wire:click="copyFromYesterday"
                    class="px-5 py-2.5 bg-purple-600 text-white font-semibold rounded-xl shadow hover:bg-purple-700 transition flex items-center gap-2">
                    📅 Salin Kemarin
                </button>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-auto px-6 pb-6">
        
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mt-6">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mt-6">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- ==================== MODE: PER SESI ==================== -->
        @if ($viewMode === 'sesi')
            
            <!-- Tab Sesi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-2 mt-6 overflow-x-auto">
                <div class="flex gap-2 min-w-max">
                    @foreach ($sesiList as $key => $sesi)
                        <button wire:click="$set('sesiAktif', '{{ $key }}')"
                            class="px-6 py-3 rounded-xl text-sm font-semibold transition whitespace-nowrap
                            {{ $sesiAktif === $key ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-100' }}">
                            {{ $sesi['icon'] }} {{ $sesi['label'] }}
                            <span class="text-xs opacity-80 ml-1">({{ $sesi['waktu'] }})</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Statistik Sesi Aktif -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                @foreach (['hadir' => ['color' => 'emerald', 'icon' => ''], 
                           'izin' => ['color' => 'amber', 'icon' => ''], 
                           'sakit' => ['color' => 'blue', 'icon' => ''], 
                           'alpa' => ['color' => 'rose', 'icon' => '']] as $status => $config)
                    <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-semibold text-gray-600 uppercase">{{ $status }}</p>
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-{{ $config['color'] }}-100 text-2xl">
                                {{ $config['icon'] }}
                            </div>
                        </div>
                        <p class="text-4xl font-bold mt-4 tracking-tight text-gray-900">
                            {{ $statistik[$sesiAktif][$status] ?? 0 }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Bulk Update -->
            <div class="bg-white rounded-2xl shadow-sm border p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">⚡ Update Status Massal</h2>
                <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                    <select wire:model="bulkStatus"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 bg-gray-50 focus:ring-2 focus:ring-blue-500">
                        <option value="hadir"> Hadir</option>
                        <option value="izin"> Izin</option>
                        <option value="sakit"> Sakit</option>
                        <option value="alpa"> Alpa</option>
                    </select>

                    <button wire:click="updateBulk"
                        class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-xl shadow hover:bg-green-700 transition">
                        Terapkan ke Semua Centang
                    </button>
                </div>
            </div>

            <!-- Tabel Kehadiran Per Sesi -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mt-6">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-blue-50 border-b">
                        <tr>
                            <th class="p-4 text-left">
                                <input type="checkbox" 
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600">
                            </th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-700">Nama Santri</th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-700">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($santriList as $santri)
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="p-4">
                                    <input type="checkbox"
                                        wire:model="selectedSantri.{{ $santri['id'] }}"
                                        class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="p-4 font-semibold text-gray-900">{{ $santri['nama'] }}</td>
                                <td class="p-4">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium">
                                        {{ $santri['kelas'] }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex gap-2 flex-wrap">
                                        @foreach (['hadir', 'izin', 'sakit', 'alpa'] as $status)
                                            <button wire:click="updateKehadiran({{ $santri['id'] }}, '{{ $status }}')"
                                                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                                                {{ $santri['status'] === $status 
                                                    ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' 
                                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                                @if ($status === 'hadir') ✅
                                                @elseif ($status === 'izin') 📝
                                                @elseif ($status === 'sakit') 🤒
                                                @else ❌
                                                @endif
                                                {{ ucfirst($status) }}
                                            </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 p-8">
                                    📭 Tidak ada santri bimbingan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @else
            <!-- ==================== MODE: HARIAN ==================== -->
            
            <!-- Statistik Harian -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                @foreach (['hadir' => ['color' => 'emerald', 'icon' => ''], 
                           'izin' => ['color' => 'amber', 'icon' => ''], 
                           'sakit' => ['color' => 'blue', 'icon' => ''], 
                           'alpa' => ['color' => 'rose', 'icon' => '']] as $status => $config)
                    <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-semibold text-gray-600 uppercase">{{ $status }}</p>
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-{{ $config['color'] }}-100 text-2xl">
                                {{ $config['icon'] }}
                            </div>
                        </div>
                        <p class="text-4xl font-bold mt-4 tracking-tight text-gray-900">
                            {{ $statistikHarian[$status] ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Total semua sesi</p>
                    </div>
                @endforeach
            </div>

            <!-- Tabel Kehadiran Harian -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto mt-6">
                <table class="w-full min-w-max">
                    <thead class="bg-gradient-to-r from-gray-50 to-blue-50 border-b">
                        <tr>
                            <th class="p-4 text-left text-sm font-semibold text-gray-700 sticky left-0 bg-gray-50">Nama</th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            @foreach ($sesiList as $key => $sesi)
                                <th class="p-4 text-center text-sm font-semibold text-gray-700">
                                    <div class="flex flex-col items-center">
                                        <span class="text-lg">{{ $sesi['icon'] }}</span>
                                        <span class="text-xs">{{ $sesi['label'] }}</span>
                                    </div>
                                </th>
                            @endforeach
                            <th class="p-4 text-center text-sm font-semibold text-gray-700">Persentase</th>
                            <th class="p-4 text-center text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($santriList as $santri)
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="p-4 font-semibold text-gray-900 sticky left-0 bg-white">{{ $santri['nama'] }}</td>
                                <td class="p-4">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium">
                                        {{ $santri['kelas'] }}
                                    </span>
                                </td>
                                @foreach (array_keys($sesiList) as $sesi)
                                    <td class="p-4 text-center">
                                        @php
                                            $status = $santri[$sesi] ?? 'belum';
                                            $statusConfig = [
                                                'hadir' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => '✅'],
                                                'izin' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => '📝'],
                                                'sakit' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => '🤒'],
                                                'alpa' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => '❌'],
                                                'belum' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-500', 'icon' => '⏳'],
                                            ];
                                            $config = $statusConfig[$status];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['icon'] }}
                                        </span>
                                    </td>
                                @endforeach
                                <td class="p-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl font-bold text-gray-900">{{ $santri['persentase'] }}%</span>
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all"
                                                 style="width: {{ $santri['persentase'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $badgeClass = match(true) {
                                            $santri['persentase'] >= 80 => 'bg-green-100 text-green-700',
                                            $santri['persentase'] >= 60 => 'bg-blue-100 text-blue-700',
                                            $santri['persentase'] >= 40 => 'bg-yellow-100 text-yellow-700',
                                            default => 'bg-red-100 text-red-700'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }}">
                                        {{ $santri['status_harian'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 p-8">
                                    📭 Tidak ada santri bimbingan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @endif

    </div>

</div>