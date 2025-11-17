@section('title', 'Dashboard')
</head>
<body>
        <!-- Sidebar -->
        <!-- Main Content -->
        <div class="min-h-screen bg-gray-50">
            <!-- HEADER -->
            <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
                <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Dashboard Ustadz</h2>
                        <p style="font-size: 0.875rem; color: #6b7280;">Pantau progress hafalan santri binaan Anda</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <a href="{{ route('ustadz.input-setoran') }}" wire:navigate
                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem;
                                border: none; font-weight: 500; cursor: pointer;
                                display: inline-flex; align-items: center; gap: 0.5rem;
                                text-decoration: none;">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Input Setoran Baru
                        </a>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main style="flex: 1; overflow-y: auto; padding: 1.5rem; background-color: #f9fafb;">
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                    
                    <div style="background: linear-gradient(to bottom right, #10b981, #059669); border-radius: 0.75rem; padding: 1.5rem; color: white;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <div style="background-color: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                                <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $santriBinaan->count() }}</h3>
                        <p style="font-size: 0.875rem; color: rgba(255,255,255,0.8);">Santri Binaan</p>
                    </div>
                    
                    <div style="background: linear-gradient(to bottom right, #3b82f6, #2563eb); border-radius: 0.75rem; padding: 1.5rem; color: white;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <div style="background-color: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                                <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span style="font-size: 0.75rem; background-color: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 9999px;">Hari Ini</span>
                        </div>
                        <h3 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $setoranHariIni->count() }}</h3>
                        <p style="font-size: 0.875rem; color: rgba(255,255,255,0.8);">Setoran Selesai</p>
                    </div>
                    
                    <div style="background: linear-gradient(to bottom right, #f59e0b, #d97706); border-radius: 0.75rem; padding: 1.5rem; color: white;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <div style="background-color: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                                <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $belumSetor ?? 0 }}</h3>
                        <p style="font-size: 0.875rem; color: rgba(255,255,255,0.8);">Belum Setoran</p>

                    </div>
                    
                    <div style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed); border-radius: 0.75rem; padding: 1.5rem; color: white;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <div style="background-color: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                                <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $rataJuz ?? 0 }}</h3>
                        <p style="font-size: 0.875rem; color: rgba(255,255,255,0.8);">Rata-rata Juz</p>
                    </div>
                    
                </div>
                
                <!-- Jadwal Hari Ini & Progress Chart -->
            </main>
        </div>                
        <script>
        function showSection(section) {
            alert('Navigasi ke halaman ' + section + ' - Ini adalah demo dashboard');
        }
        
        function logout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                alert('Logout berhasil - Akan redirect ke landing page');
                // window.location.href = '/';
            }
        }
        
        
    </script>
