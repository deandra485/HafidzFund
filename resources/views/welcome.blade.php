<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SantriFund - Sistem Manajemen Hafalan & Keuangan Santri</title>
    <link rel="icon" type="image/png" href="public/enno/image/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="public/enno/image/favicon/favicon.svg" />
    <link rel="shortcut icon" href="public/enno/image/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="public/enno/image/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="public/enno/image/favicon/site.webmanifest" />
    <script src="https://cdn.tailwindcss.com"></script>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #fffbf8 0%, #ff963a 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        body {
            margin: 0;
            padding: 0;
        }
    </style>
    @livewireStyles
</head>
<body style="background-color: #f9fafb; margin: 0; padding: 0;">
    
    <!-- Navigation -->
    <nav style="background-color: white; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); position: fixed; width: 100%; top: 0; z-index: 50;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 1rem 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center;">
                    <img src="{{ asset('enno/assets/img/logo2.png') }}" style="width: 2.5rem; height: 2.5rem; " fill="currentColor" viewBox="0 0 20 20">
                    </img>
                    <span style="margin-left: 0.75rem; font-size: 1.5rem; font-weight: 700; color: #1f2937;">HafidzFund</span>
                </div>
                <div style="display: flex; align-items: center; gap: 2rem;">
                    <a href="#fitur" style="color: #4b5563; font-weight: 500; text-decoration: none;">Fitur</a>
                    <a href="#tentang" style="color: #4b5563; font-weight: 500; text-decoration: none;">Tentang</a>
                    <a href="#kontak" style="color: #4b5563; font-weight: 500; text-decoration: none;">Kontak</a>
                    <a wire:navigate href="{{ route('auth.login') }}" style="background-color:#FF8B26; color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem; border: none; font-weight: 500; cursor: pointer; text-decoration: none;">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg" style="padding-top: 8rem; padding-bottom: 5rem; padding-left: 1.5rem; padding-right: 1.5rem;">
        <div style="max-width: 1280px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 3rem; align-items: center;">
                <div style="color: rgb(30, 28, 28);">
                    <h1 style="font-size: 3.5rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.2;">
                        Kelola Hafalan & Keuangan Santri dengan Mudah
                    </h1>
                    <p style="font-size: 1.25rem; margin-bottom: 2rem; color:  #FF8B26;">
                        Platform digital terintegrasi untuk memantau progress hafalan Al-Qur'an dan manajemen keuangan santri secara real-time.
                    </p>
                    <div style="display: flex; gap: 1rem;">
                        <button onclick="showLoginModal()" style="background-color: white; color: #FF8B26; padding: 1rem 2rem; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer;">
                            Mulai Sekarang
                        </button>
                        <a href="#fitur" style="border: 2px solid white; color: white; padding: 1rem 2rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: inline-block;">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="animate-float">
                    <div style="background-color: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); padding: 2rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="background-color: #faf5ff; padding: 1rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 600; color: #374151;">Total Santri Aktif</span>
                                <span style="font-size: 1.5rem; font-weight: 700; color: #9333ea;">245</span>
                            </div>
                            <div style="background-color: #f0fdf4; padding: 1rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 600; color: #374151;">Hafalan 30 Juz</span>
                                <span style="font-size: 1.5rem; font-weight: 700; color: #16a34a;">18</span>
                            </div>
                            <div style="background-color: #eff6ff; padding: 1rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 600; color: #374151;">Setoran Hari Ini</span>
                                <span style="font-size: 1.5rem; font-weight: 700; color: #2563eb;">67</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" style="padding: 5rem 1.5rem; background-color: white;">
        <div style="max-width: 1280px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">Fitur Unggulan</h2>
                <p style="font-size: 1.25rem; color: #6b7280;">Solusi lengkap untuk manajemen pesantren modern</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
                <!-- Feature 1 -->
                <div class="card-hover" style="background: linear-gradient(to bottom right, #faf5ff, white); padding: 2rem; border-radius: 1rem; border: 1px solid #e9d5ff;">
                    <div style="background-color: #9333ea; width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">Monitoring Hafalan</h3>
                    <p style="color: #6b7280;">Pantau progress hafalan setiap santri secara detail dengan sistem penilaian yang komprehensif.</p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover" style="background: linear-gradient(to bottom right, #f0fdf4, white); padding: 2rem; border-radius: 1rem; border: 1px solid #bbf7d0;">
                    <div style="background-color: #16a34a; width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">Manajemen Keuangan</h3>
                    <p style="color: #6b7280;">Kelola pembayaran SPP, uang jajan santri, dan laporan keuangan dengan sistem yang terintegrasi.</p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover" style="background: linear-gradient(to bottom right, #eff6ff, white); padding: 2rem; border-radius: 1rem; border: 1px solid #bfdbfe;">
                    <div style="background-color: #2563eb; width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">Laporan & Analitik</h3>
                    <p style="color: #6b7280;">Generate laporan dan visualisasi data untuk memudahkan pengambilan keputusan.</p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover" style="background: linear-gradient(to bottom right, #fefce8, white); padding: 2rem; border-radius: 1rem; border: 1px solid #fde047;">
                    <div style="background-color: #ca8a04; width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">Jadwal Setoran</h3>
                    <p style="color: #6b7280;">Atur jadwal setoran dan pantau kehadiran santri dengan sistem yang efisien.</p>
                </div>

                <!-- Feature 5 -->
                <div class="card-hover" style="background: linear-gradient(to bottom right, #fef2f2, white); padding: 2rem; border-radius: 1rem; border: 1px solid #fecaca;">
                    <div style="background-color: #dc2626; width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">Multi User Role</h3>
                    <p style="color: #6b7280;">Akses terpisah untuk Admin dan Ustadz dengan hak akses yang disesuaikan.</p>
                </div>

                <!-- Feature 6 -->
                <div class="card-hover" style="background: linear-gradient(to bottom right, #eef2ff, white); padding: 2rem; border-radius: 1rem; border: 1px solid #c7d2fe;">
                    <div style="background-color: #4f46e5; width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">Export Data</h3>
                    <p style="color: #6b7280;">Export laporan ke format PDF dan Excel untuk dokumentasi dan arsip.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="gradient-bg" style="padding: 5rem 1.5rem;">
        <div style="max-width: 1280px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; text-align: center; color: white;">
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">500+</div>
                    <div style="font-size: 1.25rem; color: #000000;">Santri Terdaftar</div>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">50+</div>
                    <div style="font-size: 1.25rem; color: #000000;">Ustadz Aktif</div>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">10K+</div>
                    <div style="font-size: 1.25rem; color: #000000;">Setoran Tercatat</div>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">98%</div>
                    <div style="font-size: 1.25rem; color: #000000;">Kepuasan Pengguna</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section style="padding: 5rem 1.5rem; background-color: white;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem;">Siap Memulai Transformasi Digital?</h2>
            <p style="font-size: 1.25rem; color: #6b7280; margin-bottom: 2rem;">
                Bergabunglah dengan ratusan pesantren yang telah merasakan kemudahan mengelola hafalan dan keuangan santri dengan SantriFund.
            </p>
            <button onclick="showLoginModal()" style="background-color: #FF8B26; color: white; padding: 1rem 3rem; border-radius: 0.5rem; font-size: 1.125rem; font-weight: 600; border: none; cursor: pointer;">
                Login Sekarang
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: #111827; color: white; padding: 3rem 1.5rem;">
        <div style="max-width: 1280px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <img src="" alt="">
                        <span style="margin-left: 0.5rem; font-size: 1.25rem; font-weight: 700;">SantriFund</span>
                    </div>
                    <p style="color: #9ca3af;">Platform digital untuk manajemen hafalan dan keuangan santri.</p>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 1rem;">Produk</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; color: #9ca3af;">
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Fitur</a>
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Harga</a>
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Demo</a>
                    </div>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 1rem;">Perusahaan</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; color: #9ca3af;">
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Tentang Kami</a>
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Kontak</a>
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Blog</a>
                    </div>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 1rem;">Dukungan</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; color: #9ca3af;">
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Bantuan</a>
                        <a href="#" style="color: #9ca3af; text-decoration: none;">Dokumentasi</a>
                        <a href="#" style="color: #9ca3af; text-decoration: none;">FAQ</a>
                    </div>
                </div>
            </div>
            <div style="border-top: 1px solid #374151; padding-top: 2rem; text-align: center; color: #9ca3af;">
                <p>&copy; 2025 SantriFund. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
   @livewireScripts
</body>
</html>