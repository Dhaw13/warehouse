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
                            <select name="supplier" id="supplier" class="form-control select2-tags @error('supplier') is-invalid @enderror" required>
                                <option value="">-- Pilih atau Ketik Supplier Baru --</option>
                                @foreach($suppliers as $supp)
                                    <option value="{{ $supp->nama_supplier }}" {{ old('supplier') == $supp->nama_supplier ? 'selected' : '' }}>
                                        {{ $supp->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">💡 Ketik nama baru jika supplier belum ada</small>
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
                                        <select name="items[0][nama_barang]" class="form-control barang-select select2-tags-barang" required onchange="fillSatuan(this)">
                                            <option value="">-- Pilih atau Ketik Barang Baru --</option>
                                            @foreach($barangs as $brg)
                                                <option value="{{ $brg->nama_barang }}" data-satuan="{{ $brg->satuan }}">
                                                    {{ $brg->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][qty]" class="form-control qty-input" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][satuan]" class="form-control satuan-input" required placeholder="Kg, Pcs, Box">
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
                <button type="submit" class="btn btn-success">💾 Simpan PO</button>
            </div>
        </form>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let rowIndex = 1;

        // Initialize Select2 untuk Supplier
        $(document).ready(function() {
            $('#supplier').select2({
                theme: 'bootstrap-5',
                tags: true,
                createTag: function (params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newTag: true
                    }
                },
                placeholder: '-- Pilih atau Ketik Supplier Baru --',
                allowClear: true
            });

            // Initialize Select2 untuk Barang pertama
            initBarangSelect2();
        });

        function initBarangSelect2() {
            $('.select2-tags-barang').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        theme: 'bootstrap-5',
                        tags: true,
                        createTag: function (params) {
                            return {
                                id: params.term,
                                text: params.term,
                                newTag: true
                            }
                        },
                        placeholder: '-- Pilih atau Ketik Barang Baru --',
                        allowClear: true
                    });
                }
            });
        }

        const barangOptions = `
            <option value="">-- Pilih atau Ketik Barang Baru --</option>
            @foreach($barangs as $brg)
                <option value="{{ $brg->nama_barang }}" data-satuan="{{ $brg->satuan }}">{{ $brg->nama_barang }}</option>
            @endforeach
        `;

        function fillSatuan(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan');
            const row = selectElement.closest('tr');
            const satuanInput = row.querySelector('.satuan-input');
            if (satuan) {
                satuanInput.value = satuan;
            }
        }

        function addRow() {
            const container = document.getElementById('itemsContainer');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = `
                <td><select name="items[${rowIndex}][nama_barang]" class="form-control barang-select select2-tags-barang" required onchange="fillSatuan(this)">${barangOptions}</select></td>
                <td><input type="number" name="items[${rowIndex}][qty]" class="form-control qty-input" min="1" value="1" required></td>
                <td><input type="text" name="items[${rowIndex}][satuan]" class="form-control satuan-input" required placeholder="Kg, Pcs, Box"></td>
                <td><input type="number" name="items[${rowIndex}][harga_satuan]" class="form-control harga-input" min="0" step="0.01" required></td>
                <td><input type="text" class="form-control subtotal-display" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></td>
            `;
            container.appendChild(newRow);
            rowIndex++;
            
            // Re-initialize Select2 untuk baris baru
            initBarangSelect2();
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

        attachCalculators();
        calculateTotal();
    </script>
</x-app-layout>