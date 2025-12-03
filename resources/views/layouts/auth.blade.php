<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="public/image/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="public/image/favicon/favicon.svg" />
    <link rel="shortcut icon" href="public/image/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="public/image/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="public/image/favicon/site.webmanifest" />
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased bg-gray-50">
    
    <div>
        {{ $slot }}
    </div>
   
            
@livewireScripts
</body>
</html>