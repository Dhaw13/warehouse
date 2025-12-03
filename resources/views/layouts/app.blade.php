<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warehouse Logistic</title>

    <!-- Fonts & CSS -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<!-- Di resources/views/layouts/app.blade.php -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            display: flex;
        }

        .main-navbar {
            position: fixed;
            left: 250px;
            right: 0;
            top: 0;
            height: 60px;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            z-index: 10;
            transition: all 0.3s ease;
        }

        .main-navbar.full { left: 0; }

        .main-content {
            margin-left: 250px;
            padding: 80px 30px 30px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .main-content.full { margin-left: 0; }

    
        .dropdown-item.text-danger {
            color: #d32f2f !important; /* Merah soft (lebih halus dari merah default) */
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 6px;
            padding: 8px 12px;
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(128, 0, 0, 0.12) !important; /* Latar marun transparan */
            color: #800000 !important; /* Text jadi marun saat hover */
            transform: translateX(4px);
            box-shadow: 0 2px 6px rgba(128, 0, 0, 0.15);
        }

        .dropdown-item.text-danger:focus {
            outline: none;
            box-shadow: inset 0 0 0 2px rgba(128, 0, 0, 0.2);
        }

        #sidebar .nav-link {
            color: white;
            text-decoration: none;
            transition: all 0.25s ease;
            border-left: 4px solid transparent;
            border-radius: 0 4px 4px 0;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #ffffff;
            transform: translateX(4px);
        }

        #sidebar .nav-link.active {
            background-color: rgba(0, 0, 0, 0.25);
            border-left: 4px solid #ffffff;
            font-weight: 600;
        }

        #sidebar .nav-link.active:hover {
            background-color: rgba(0, 0, 0, 0.35);
        }

        /* === Override Bootstrap Table — tetap pakai class asli === */
.table.table-bordered.table-striped {
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    background: white;
}

/* Header tabel — pakai warna marun */
.table.table-bordered.table-striped thead {
    background: linear-gradient(to right, #800000, #a00000) !important;
    color: white !important;
}

.table.table-bordered.table-striped thead th {
    font-weight: 600;
    padding: 14px 16px;
    text-align: center;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none !important;
    color: white !important;
}

/* Body tabel */
.table.table-bordered.table-striped tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    font-size: 0.95rem;
}

.table.table-bordered.table-striped tbody tr:hover {
    background-color: #fdf6f6 !important;
}

/* Kolom Aksi */
.aksi-col {
    text-align: center;
    white-space: nowrap;
    width: 160px;
}

/* Tombol di dalam tabel */
.table .btn {
    font-size: 0.85rem;
    padding: 4px 10px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.table .btn-warning {
    background-color: #ffc107;
    border-color: #e0a800;
    color: #212529;
}

.table .btn-warning:hover {
    background-color: #e0a800;
    border-color: #c69500;
}

.table .btn-danger {
    background-color: #dc3545;
    border-color: #c82333;
}

.table .btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

    /* === Custom Permission Badge === */
.permission-badge {
    display: inline-block;
    background-color: rgba(128, 0, 0, 0.12); /* Latar marun transparan */
    color: #800000;                           /* Teks marun */
    padding: 4px 10px;
    border-radius: 20px;                      /* Bentuk pill */
    font-size: 0.85rem;
    font-weight: 500;
    margin: 2px;
    border: 1px solid rgba(128, 0, 0, 0.2);
    transition: all 0.15s ease;
}

.permission-badge:hover {
    background-color: rgba(128, 0, 0, 0.2);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Responsif: tata letak rapi di mobile */
@media (max-width: 768px) {
    .permission-badge {
        font-size: 0.8rem;
        padding: 3px 8px;
    }
}

/* Responsif */
@media (max-width: 768px) {
    .table-responsive {
        box-shadow: none;
    }
    .table thead {
        display: none;
    }
    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }
    .table tr {
        margin-bottom: 16px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px;
    }
    .table td {
        text-align: right;
        padding-left: 50% !important;
        position: relative;
    }
    .table td::before {
        content: attr(data-label) ": ";
        position: absolute;
        left: 16px;
        font-weight: 600;
        color: #333;
    }

    
}
    </style>
</head>
<body>

    {{-- Sidebar --}}
    @include('layouts.navigation')

    {{-- Header/Navbar --}}
    <nav class="main-navbar" id="navbar">
        <i class="bi bi-list menu-toggle" id="menu-toggle"></i>
        <div class="profile-section d-flex align-items-center gap-2">
            <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" width="35" height="35" class="rounded-circle" alt="Profile">
            @auth
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </nav>

    {{-- Konten utama --}}
    <main class="main-content" id="main-content">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

 
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currentPath = window.location.pathname;

        
            document.querySelectorAll('#sidebar nav a').forEach(link => {
                const href = link.getAttribute('href');
                if (href && (currentPath === href || currentPath.startsWith(href + '/'))) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    <script>
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
</body>
</html>