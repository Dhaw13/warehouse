<x-app-layout>
    <div class="container mt-4">
        <h3>Buat Purchase Order Baru</h3>

        <form action="{{ route('po.store') }}" method="POST" id="formPO">
            @csrf

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_po" class="form-label">Tanggal PO <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_po" id="tanggal_po" 
                                   class="form-control @error('tanggal_po') is-invalid @enderror" 
                                   value="{{ old('tanggal_po', date('Y-m-d')) }}" required>
                            @error('tanggal_po')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="supplier" id="supplier" 
                                   class="form-control @error('supplier') is-invalid @enderror" 
                                   value="{{ old('supplier') }}" required>
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Detail Item PO</span>
                    <button type="button" class="btn btn-sm btn-light" onclick="addRow()">
                        <i class="bi bi-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableItems">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Nama Barang</th>
                                    <th style="width: 15%">Qty</th>
                                    <th style="width: 15%">Satuan</th>
                                    <th style="width: 20%">Harga Satuan</th>
                                    <th style="width: 15%">Subtotal</th>
                                    <th style="width: 5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsContainer">
                                <tr class="item-row">
                                    <td>
                                        <input type="text" name="items[0][nama_barang]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][qty]" class="form-control qty-input" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][satuan]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][harga_satuan]" class="form-control harga-input" min="0" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal-display" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                    <td colspan="2"><strong id="totalHarga">Rp 0</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('po.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success">Simpan PO</button>
            </div>
        </form>
    </div>

    <script>
        let rowIndex = 1;

        function addRow() {
            const container = document.getElementById('itemsContainer');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = `
                <td><input type="text" name="items[${rowIndex}][nama_barang]" class="form-control" required></td>
                <td><input type="number" name="items[${rowIndex}][qty]" class="form-control qty-input" min="1" value="1" required></td>
                <td><input type="text" name="items[${rowIndex}][satuan]" class="form-control" required></td>
                <td><input type="number" name="items[${rowIndex}][harga_satuan]" class="form-control harga-input" min="0" step="0.01" required></td>
                <td><input type="text" class="form-control subtotal-display" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></td>
            `;
            container.appendChild(newRow);
            rowIndex++;
            attachCalculators();
        }

        function removeRow(btn) {
            if (document.querySelectorAll('.item-row').length > 1) {
                btn.closest('tr').remove();
                calculateTotal();
            } else {
                alert('Minimal harus ada 1 item!');
            }
        }

        function attachCalculators() {
            document.querySelectorAll('.qty-input, .harga-input').forEach(input => {
                input.removeEventListener('input', calculateRow);
                input.addEventListener('input', calculateRow);
            });
        }

        function calculateRow(e) {
            const row = e.target.closest('tr');
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
            const subtotal = qty * harga;
            row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                total += qty * harga;
            });
            document.getElementById('totalHarga').textContent = formatRupiah(total);
        }

        function formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Initialize
        attachCalculators();
        calculateTotal();
    </script>
</x-app-layout>