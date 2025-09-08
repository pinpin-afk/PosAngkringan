<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Management - POS Angkringan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div id="app"></div>
    
    @vite(['resources/js/app.js'])
    
    <script>
        // Set user data for admin dashboard
        window.user = @json(auth('admin')->user());
        
        // Force admin dashboard to show roles page
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for Vue to mount, then set the page
            setTimeout(() => {
                if (window.Vue && window.Vue.config && window.Vue.config.globalProperties) {
                    // Vue is ready, trigger page change
                    window.dispatchEvent(new CustomEvent('admin-page-change', { 
                        detail: { page: 'roles', title: 'Manajemen Role' } 
                    }));
                }
            }, 100);
        });
    </script>
</body>
</html>
