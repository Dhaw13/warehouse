<x-app-layout>
    <div class="container mt-4">
        <h3>Edit Data Barang</h3>

        <form action="{{ route('pbarang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="kode_barang" class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang"
                       class="form-control @error('kode_barang') is-invalid @enderror"
                       value="{{ old('kode_barang', $barang->kode_barang) }}">
                @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang"
                       class="form-control @error('nama_barang') is-invalid @enderror"
                       value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" min="0"
                           class="form-control @error('jumlah') is-invalid @enderror"
                           value="{{ old('jumlah', $barang->jumlah) }}" required>
                    @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" name="satuan" id="satuan"
                           class="form-control @error('satuan') is-invalid @enderror"
                           value="{{ old('satuan', $barang->satuan) }}">
                    @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="kondisi" class="form-label">Kondisi</label>
                <select name="kondisi" id="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                    <option value="">-- Pilih Kondisi --</option>
                    @foreach(['baru','baik','bekas','rusak','lainnya'] as $kondisi)
                        <option value="{{ $kondisi }}" {{ old('kondisi', $barang->kondisi) == $kondisi ? 'selected' : '' }}>
                            {{ ucfirst($kondisi) }}
                        </option>
                    @endforeach
                </select>
                @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                       class="form-control @error('tanggal_masuk') is-invalid @enderror"
                       value="{{ old('tanggal_masuk', $barang->tanggal_masuk->format('Y-m-d')) }}" required>
                @error('tanggal_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('pbarang.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</x-app-layout>
