<x-app-layout>
    <div class="container mt-4">
        <h3>Ubah Verifikasi: {{ $verifikasi->barang->nama_barang }}</h3>

        <form action="{{ route('verifikasi.update', $verifikasi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="verified" {{ $verifikasi->status === 'verified' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ $verifikasi->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control">{{ old('catatan', $verifikasi->catatan) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</x-app-layout><x-app-layout>
    <div class="container mt-4">
        <h3>Ubah Verifikasi: {{ $verifikasi->barang->nama_barang }}</h3>

        <form action="{{ route('verifikasi.update', $verifikasi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="verified" {{ $verifikasi->status === 'verified' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ $verifikasi->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control">{{ old('catatan', $verifikasi->catatan) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</x-app-layout>