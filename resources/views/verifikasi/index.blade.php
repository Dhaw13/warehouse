<x-app-layout>
    <div class="container mt-4">
        <h3>Verifikasi Barang</h3>
        <p class="text-muted">Verifikasi kesesuaian barang fisik dengan data master</p>

        @if($barangs->isEmpty())
            <div class="alert alert-info">
                Tidak ada barang yang perlu diverifikasi.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $index => $barang)
                            <tr>
                                <td>{{ $barangs->firstItem() + $index }}</td>
                                <td>{{ $barang->kode_barang }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->jumlah }}</td>
                                <td>{{ $barang->satuan ?? '-' }}</td>
                                <td>{{ $barang->kondisi }}</td>
                                <td>
                                    @if($barang->verifikasiBarang->isNotEmpty())
                                        <a href="{{ route('verifikasi.edit', $barang->verifikasiBarang->first()->id) }}" 
                                           class="btn btn-sm btn-warning">
                                            Ubah Verifikasi
                                        </a>
                                        <span class="badge bg-{{ $barang->verifikasiBarang->first()->status === 'verified' ? 'success' : 'danger' }} ms-1">
                                            {{ $barang->verifikasiBarang->first()->status }}
                                        </span>
                                    @else
                                        <a href="{{ route('verifikasi.create', $barang->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            Verifikasi
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $barangs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>