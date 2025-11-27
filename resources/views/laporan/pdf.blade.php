<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penerimaan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #800000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #800000;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #800000;
            color: white;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENERIMAAN BARANG</h2>
        <p><strong>MY WAREHOUSE SYSTEM</strong></p>
        
        @if($laporan->total_barang > 0)
            <span class="status-badge status-approved">✅ APPROVED - BARANG DITERIMA</span>
        @else
            <span class="status-badge status-rejected">❌ REJECTED - BARANG DITOLAK</span>
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td width="150"><strong>Periode</strong></td>
            <td>: {{ $laporan->periode }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Cetak</strong></td>
            <td>: {{ $laporan->tanggal_cetak->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Nama Barang</strong></td>
            <td>: {{ $barang->nama_barang ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Kode Barang</strong></td>
            <td>: {{ $barang->kode_barang ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>: 
                @if($laporan->total_barang > 0)
                    <strong style="color: #155724;">DITERIMA</strong>
                @else
                    <strong style="color: #721c24;">DITOLAK</strong>
                @endif
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Kondisi</th>
                <th>Tanggal Masuk</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $barang->nama_barang ?? '-' }}</td>
                <td>{{ $laporan->total_barang }}</td>
                <td>{{ $barang->satuan ?? '-' }}</td>
                <td>{{ ucfirst($barang->kondisi ?? '-') }}</td>
                <td>{{ $barang->tanggal_masuk ? $barang->tanggal_masuk->format('d M Y') : '-' }}</td>
            </tr>
        </tbody>
    </table>

    @if($laporan->total_barang == 0)
        <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffc107;">
            <strong>Catatan:</strong> Barang ini ditolak dan TIDAK menambah stok gudang.
        </div>
    @endif

    <div class="footer">
        <p>
            Surabaya, {{ now()->format('d F Y') }}<br>
            <strong>Admin Gudang</strong>
        </p>
        <br><br><br>
        <p>__________________________</p>
    </div>
</body>
</html>