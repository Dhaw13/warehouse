<x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>📄 Detail Laporan Penerimaan</h3>
            <div>
                <a href="{{ route('laporan.download', $laporan->id) }}" 
                   class="btn btn-danger" 
                   target="_blank">
                    <i class="bi bi-file-pdf"></i> Download PDF
                </a>
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="card mb-3">
            <div class="card-body text-center">
                @if($laporan->total_barang > 0)
                    <span class="badge bg-success" style="font-size: 1.2rem; padding: 10px 30px;">
                        ✅ STATUS: APPROVED - BARANG DITERIMA
                    </span>
                @else
                    <span class="badge bg-danger" style="font-size: 1.2rem; padding: 10px 30px;">
                        ❌ STATUS: REJECTED - BARANG DITOLAK
                    </span>
                @endif
            </div>
        </div>

        <!-- Info Laporan -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📋 Informasi Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">No. Laporan</th>
                                <td><strong>LAP-{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Periode</th>
                                <td>{{ $laporan->periode }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Cetak</th>
                                <td>{{ $laporan->tanggal_cetak->format('d F Y, H:i') }} WIB</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Kode Barang</th>
                                <td>{{ $barang->kode_barang ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td><strong>{{ $barang->nama_barang ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Jumlah Diterima</th>
                                <td>
                                    <strong class="{{ $laporan->total_barang > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $laporan->total_barang }} {{ $barang->satuan ?? '' }}
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Barang -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📦 Detail Barang</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Kondisi</th>
                            <th>Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $barang->kode_barang ?? '-' }}</td>
                            <td><strong>{{ $barang->nama_barang ?? '-' }}</strong></td>
                            <td class="text-center"><strong>{{ $laporan->total_barang }}</strong></td>
                            <td class="text-center">{{ $barang->satuan ?? '-' }}</td>
                            <td class="text-center">
                                @if($laporan->total_barang > 0)
                                    <span class="badge bg-success">{{ ucfirst($barang->kondisi ?? 'Baik') }}</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $barang->tanggal_masuk ? $barang->tanggal_masuk->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if($laporan->total_barang == 0)
                    <div class="alert alert-warning mt-3">
                        <strong>⚠️ CATATAN PENTING:</strong><br>
                        Barang ini DITOLAK pada proses verifikasi dan <strong>TIDAK menambah stok gudang</strong>. 
                        Silakan koordinasi dengan supplier terkait pengembalian atau penggantian barang.
                    </div>
                @else
                    <div class="alert alert-success mt-3">
                        <strong>✅ INFORMASI:</strong><br>
                        Barang telah diterima dengan baik dan <strong>STOK TELAH DITAMBAHKAN</strong> ke sistem gudang.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>