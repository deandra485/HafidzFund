<aside id="sidebar" style="width: 16rem; height: 100vh; position: fixed; background-color: white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); flex-shrink: 0; z-index: 9999; overflow-y: auto;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center;">
            <img src="{{ asset('enno/assets/img/logo2.png') }}" style="width: 2.5rem; height: 2.5rem;">
            <div style="margin-left: 0.75rem;">
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">HafidzFund</h1>
                <p style="font-size: 0.75rem; color: #6b7280;">Admin Panel</p>
            </div>
        </div>
    </div>

    <nav style="padding: 1rem; overflow-y: auto; height: calc(100vh - 13rem);">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem; text-decoration: none;
                {{ request()->routeIs('admin.dashboard') 
                    ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <!-- Data Santri -->
            <a href="{{ route('admin.data-santri') }}"
                wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem; text-decoration: none;
                {{ request()->routeIs('admin.data-santri')
                    ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Data Santri
            </a>

            <!-- Keuangan -->
            <a href="{{ route('admin.manajemen-keuangan') }}"
                wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem; text-decoration: none;
                {{ request()->routeIs('admin.manajemen-keuangan')
                    ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Keuangan
            </a>

            <!-- Data Ustadz -->
            <a href="{{ route('admin.data-ustadz') }}"
                wire:navigate
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem; text-decoration: none;
                {{ request()->routeIs('admin.data-ustadz')
                    ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Data Akun
            </a>

            <!-- Laporan -->
            <a href="{{ route('admin.laporan.hafalan') }}" wire:navigate  
                style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem; text-decoration: none;
                {{ request()->routeIs('admin.laporan.hafalan')
                    ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'
                    : 'color: #6b7280;' }}">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>

            <!-- Pengaturan -->
            <a href="{{ route('admin.profile') }}" onclick="showSection('setting')" class="sidebar-item" wire:navigate
               style="display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem; text-decoration: none;
                {{ request()->routeIs('admin.profile')
                    ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'
                    : 'color: #6b7280;' }}">
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

    <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center;">
                <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 9999px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                    A
                </div>
                <div style="margin-left: 0.75rem;">
                    <p style="font-size: 0.875rem; font-weight: 600; color: #1f2937;">{{ auth()->user()->name }}</p>
                    <p style="font-size: 0.75rem; color: #6b7280;">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <livewire:auth.logout />
        </div>
    </div>
</aside>
