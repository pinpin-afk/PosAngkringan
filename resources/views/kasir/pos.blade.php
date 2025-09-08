<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - Sistem POS Angkringan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">POS Kasir</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">Kasir: {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('kasir.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- POS Interface -->
    <div id="app"></div>

    <script>
        // Pass user data to Vue component
        window.user = @json(auth()->user());
    </script>
</body>
</html>
