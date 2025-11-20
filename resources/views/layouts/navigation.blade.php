<aside class="sidebar" id="sidebar" style="
    width:250px;
    background-color:#800000;
    color:white;
    min-height:100vh;
    position:fixed;
    left:0;
    top:0;
    display:flex;
    flex-direction:column;
    box-shadow:2px 0 10px rgba(0,0,0,0.1);
    transition:all 0.3s ease;">
    
    <div class="brand text-center py-3 border-bottom border-light">
        <strong>My Warehouse</strong>
    </div>

    <div class="user-panel d-flex align-items-center p-3 border-bottom border-light">
        <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" width="40" height="40" class="rounded-circle me-2" alt="User">
        <div>
            <strong>{{ Auth::user()->name ?? 'User' }}</strong><br>
            <small>
                @foreach(Auth::user()->roles as $role)
                    {{ $role->name }}@if(!$loop->last), @endif
                @endforeach
            </small>
        </div>
    </div>

    <nav class="mt-3 flex-grow-1">
        
        @can('view barang')
            <a href="{{ route('pbarang.index') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-box-seam"></i> <span>Pencatatan Barang Masuk</span>
            </a>
        @endcan
    @can('view po')
        <a href="{{ route('po.index') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-text"></i> <span>Purchase Order (PO)</span>
        </a>
    @endcan

        @can('view laporan')    
            <a href="{{ route('laporan.index') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-clipboard-check"></i> <span>Laporan Penerimaan</span>
            </a>
        @endcan

        @can('view verifikasi')
            <a href="{{ route('verifikasi.index') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-check2-square"></i> <span>Verifikasi Barang</span>
            </a>
        @endcan
            
        @can('view permission')
            <a href="{{ route('permissions.index') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-lock"></i> <span>Perizinan</span>
            </a>
        @endcan

        @can('view role')
            <a href="{{ route('roles.index') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-person-badge"></i> <span>Role</span>
            </a>
        @endcan

        @can('view user')
            <a href="{{ route('users.index') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-people"></i> <span>User</span>
            </a>
        @endcan
    </nav>
</aside>