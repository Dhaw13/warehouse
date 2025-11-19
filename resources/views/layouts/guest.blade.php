<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Warehouse Logistic') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600,700" rel="stylesheet">

    <!-- ✅ Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ✅ Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ✅ Custom Tailwind Config for Maroon -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: '#800020', // warna maroon khas
                    }
                }
            }
        }
    </script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light font-[Poppins]">

    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg py-3 shadow-sm bg-maroon">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold text-white" href="#">
                <i class="bi bi-box-seam-fill me-2"></i> Warehouse Logistic
            </a>

            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="btn btn-light fw-semibold rounded-pill px-4 text-maroon hover:bg-gray-200 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="btn btn-light fw-semibold rounded-pill px-4 text-maroon hover:bg-gray-200 transition">
                            Login
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- ✅ Main Content -->
    <main class="flex-grow-1">
        {{ $slot }}
    </main>

    <!-- ✅ Footer -->
    <footer class="bg-maroon text-white text-center py-4 mt-auto shadow-inner">
        <small class="tracking-wide">&copy; {{ date('Y') }} Warehouse Logistic. All rights reserved.</small>
    </footer>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
