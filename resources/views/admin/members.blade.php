<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management - POS Angkringan</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div id="app"></div>
    <script>
        window.user = @json(auth()->user());
        window.logoutUrl = '{{ route("admin.logout") }}';
        window.csrfToken = '{{ csrf_token() }}';
    </script>
</body>
</html>
