<x-app-layout>
    <div class="container mt-4">
        <h3> Data Barang Masuk</h3>

        <a href="{{ route('pbarang.create') }}" class="btn btn-primary mb-3"> Tambah Barang</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Kondisi</th>
                    <th>Tanggal Masuk</th>
                    <th>Supplier</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->kode_barang ?? '-' }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->jumlah }}</td>
                        <td>{{ $barang->satuan ?? '-' }}</td>
                        <td>{{ ucfirst($barang->kondisi ?? '-') }}</td>
                        <td>{{ $barang->tanggal_masuk->format('d M Y') }}</td>
                        <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                        <td>
                            <a href="{{ route('pbarang.edit', $barang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pbarang.destroy', $barang->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin ingin menghapus barang ini?')" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data barang</td>
                    </tr>
                @endforelse 
            </tbody>
        </table>

        <div class="mt-3">
            {{ $barangs->links() }}
        </div>
    </div>
</x-app-layout>
