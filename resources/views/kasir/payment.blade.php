<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - POS Angkringan</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <!-- Payment Interface -->
    <div id="app"></div>

    <script>
        // Pass user data to Vue component
        window.user = @json(auth()->user());
        window.logoutUrl = '{{ route("kasir.logout") }}';
        window.csrfToken = '{{ csrf_token() }}';
    </script>
</body>
</html>
