<x-app-layout>
    <div class="container mt-4">
        <h3>Buat Role Baru</h3>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Role</label>
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="contoh: admin, editor, user"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Permissions</label>
                <div class="row">
                    @foreach($permissions as $perm)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="permission[]" 
                                    value="{{ $perm->name }}" 
                                    id="perm_{{ $perm->id }}"
                                    {{ in_array($perm->name, old('permission', [])) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="perm_{{ $perm->id }}">
                                    {{ $perm->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
</x-app-layout>