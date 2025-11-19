<x-app-layout>
    <div class="container mt-4">
        <h3>Buat Laporan Penerimaan Barang</h3>

        <form action="{{ route('laporan.store') }}" method="POST">
            @csrf

            {{-- Input Periode --}}
            <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Laporan Pirode</label>
                <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                       class="form-control @error('tanggal_masuk') is-invalid @enderror"
                       value="{{ old('tanggal_masuk') }}" required>
                @error('tanggal_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Pilih Barang --}}
            <div class="mb-3">
                <label for="id_barang" class="form-label">Pilih Barang</label>
                <select
                    name="id_barang"
                    id="id_barang"
                    class="form-control @error('id_barang') is-invalid @enderror"
                    required
                >
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}" {{ old('id_barang') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }} ({{ $barang->jumlah }} {{ $barang->satuan }})
                        </option>
                    @endforeach
                </select>
                @error('id_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            

            {{-- Tombol --}}
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Generate Laporan</button>
            </div>
        </form>
    </div>
</x-app-layout>
