<x-app-layout>
    <div class="container-fluid">
        <h1 class="mb-4">Dashboard</h1>

        <div class="card p-4 shadow-sm">
            <p>Selamat datang, {{ Auth::user()->name ?? 'Admin' }}!</p>

        </div>
    </div>
</x-app-layout>
