<!-- Sidebar Ustadz Panel -->
<aside id="sidebar" style="width: 16rem; height: 100vh; position: fixed; background-color: white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); flex-shrink: 0; z-index: 9999; overflow-y: auto;">
    
    <!-- Header -->
    <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center;">
            <img src="{{ asset('enno/assets/img/logo2.png') }}" style="width: 2.5rem; height: 2.5rem;">
            <div style="margin-left: 0.75rem;">
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">HafidzFund</h1>
                <p style="font-size: 0.75rem; color: #6b7280;">Ustadz Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav style="padding: 1rem; overflow-y: auto; height: calc(100vh - 13rem);">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">

            <!-- Dashboard -->
            <a href="{{ route('ustadz.dashboard') }}" wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem;
                text-decoration: none; 
                {{ request()->routeIs('ustadz.dashboard') 
                    ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;' 
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <!-- Input Setoran -->
            <a href="{{ route('ustadz.santri-binaan') }}" wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem;
                text-decoration: none;
                {{ request()->routeIs('ustadz.santri-binaan') 
                    ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;' 
                    : 'color: #6b7280;' }}">
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Santri Binaan
            </a>

            <!-- Penilaian -->
            <a href="{{ route('ustadz.kehadiran-santri') }}" wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem;
                text-decoration: none;
                {{ request()->routeIs('ustadz.kehadiran-santri') 
                    ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;' 
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                kehadiran Santri
            </a>

            <!-- Riwayat Hafalan -->
            <a href="{{ route('ustadz.riwayat-hafalan') }}" wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem;
                text-decoration: none;
                {{ request()->routeIs('ustadz.riwayat-hafalan') 
                    ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;' 
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Riwayat Hafalan
            </a>

            <!-- Monitoring Target -->
            <a href="#"
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem;
                text-decoration: none; color: #6b7280;">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Monitoring Target
            </a>

             <!-- Pengaturan -->
            <a href="#" onclick="showSection('setting')" class="sidebar-item"
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem;
                text-decoration: none; color: #6b7280;">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066
                        c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572
                        c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573
                        c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065
                        c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066
                        c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572
                        c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573
                        c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Pengaturan
            </a>

        </div>
    </nav>

    <!-- Footer User Info -->
    <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center;">
                @php
                    $user = Auth::user();
                    $initial = $user ? strtoupper(substr($user->name, 0, 1)) : 'U';
                @endphp
                <div style="width: 2.5rem; height: 2.5rem;
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    border-radius: 9999px; display: flex; align-items: center; justify-content: center;
                    color: white; font-weight: 700;">
                    {{ $initial }}
                </div>
                <div style="margin-left: 0.75rem;">
                    <p style="font-size: 0.875rem; font-weight: 600; color: #1f2937;">
                        {{ $user->name ?? 'Guest' }}
                    </p>
                    <p style="font-size: 0.75rem; color: #6b7280;">
                        {{ $user->email ?? 'Tidak ada email' }}
                    </p>
                </div>
            </div>
            <livewire:auth.logout />
        </div>
    </div>
</aside>
