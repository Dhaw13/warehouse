<x-app-layout>
    <div class="container mt-4">
        <h3>📋 Laporan Penerimaan Barang</h3>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 10%">No. Laporan</th>
                                <th style="width: 25%">Nama Barang</th>
                                <th style="width: 12%">Periode</th>
                                <th style="width: 15%">Tanggal Cetak</th>
                                <th style="width: 10%">Jumlah</th>
                                <th style="width: 13%">Status</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporans as $index => $laporan)
                                <tr>
                                    <td class="text-center">{{ $laporans->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        <strong>LAP-{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $laporan->barangs->nama_barang ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $laporan->barangs->kode_barang ?? '' }}</small>
                                    </td>
                                    <td>{{ $laporan->periode }}</td>
                                    <td>{{ $laporan->tanggal_cetak->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">
                                        <strong>{{ $laporan->total_barang }}</strong> 
                                        <small>{{ $laporan->barangs->satuan ?? '' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($laporan->total_barang > 0)
                                            <span class="badge bg-success">✅ Approved</span>
                                        @else
                                            <span class="badge bg-danger">❌ Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('laporan.show', $laporan->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                        Belum ada laporan penerimaan barang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $laporans->links() }}
                </div>
            </div>
        </div>

        <!-- Info Summary -->
        @if($laporans->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h5>Total Laporan</h5>
                            <h3 class="text-primary">{{ $laporans->total() }}</h3>
                        </div>
                        <div class="col-md-4">
                            <h5>Approved</h5>
                            <h3 class="text-success">{{ $laporans->where('total_barang', '>', 0)->count() }}</h3>
                        </div>
                        <div class="col-md-4">
                            <h5>Rejected</h5>
                            <h3 class="text-danger">{{ $laporans->where('total_barang', '=', 0)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>