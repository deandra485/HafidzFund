<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="enno/assets/img/logo1.png" rel="icon">
    <title>{{ config('app.name') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Tombol hamburger disembunyikan di desktop */
        #hamburgerBtn {
            display: none !important;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            #hamburgerBtn {
                display: block !important;
            }

            #sidebar {
                transform: translateX(-100%);
                transition: 0.3s ease;
            }

            #sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="antialiased bg-gray-50">

    <!-- Tombol Hamburger (MOBILE ONLY) -->
    <button id="hamburgerBtn"
        class="md:hidden fixed top-4 left-4 z-[9999] bg-white p-2 rounded-lg border border-gray-200 shadow">
        <div class="w-6 h-[3px] bg-gray-700 my-1"></div>
        <div class="w-6 h-[3px] bg-gray-700 my-1"></div>
        <div class="w-6 h-[3px] bg-gray-700 my-1"></div>
    </button>

    <!-- Sidebar -->
    <livewire:atom.side-user />

    <!-- Wrapper utama -->
    <div class="flex min-h-screen">

        <!-- KONTEN UTAMA -->
        <main class="flex-1 md:ml-64 p-5 w-full transition-all duration-300">
            {{ $slot }}
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="md:ml-64 p-4 bg-white shadow-inner mt-10">
        <livewire:atom.footer />
    </footer>


    <script>
        document.getElementById("hamburgerBtn").addEventListener("click", function () {
            document.getElementById("sidebar").classList.toggle("show");
        });
    </script>
</body>
@livewireScripts
</html>
