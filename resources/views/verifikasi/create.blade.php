<x-app-layout>
    <div class="container mt-4">
        <h3>Verifikasi Barang: {{ $barang->nama_barang }}</h3>
        <p class="text-muted">Masukkan hasil pemeriksaan fisik barang</p>

        <form action="{{ route('verifikasi.store', $barang->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="kualitas_valid" class="form-label">Kualitas Barang</label>
                <select name="kualitas_valid" class="form-control" required>
                    <option value="">-- Pilih Kualitas --</option>
                    <option value="baik" {{ old('kualitas_valid') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="sedang" {{ old('kualitas_valid') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="buruk" {{ old('kualitas_valid') == 'buruk' ? 'selected' : '' }}>Buruk</option>
                    <option value="rusak" {{ old('kualitas_valid') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status Verifikasi</label>
                <select name="status" class="form-control" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan Verifikasi</button>
            <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</x-app-layout>