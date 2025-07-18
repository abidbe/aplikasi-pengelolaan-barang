<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Masuk Barang #{{ $barangMasuk->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            margin-top: 20px;
            float: right;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .total-row.grand-total {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 14px;
        }

        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 60px;
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-verified {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>SISTEM PENGELOLAAN BARANG</h1>
        <h2>SURAT PENERIMAAN BARANG</h2>
        <p>No.
            {{ $barangMasuk->id }}/{{ $barangMasuk->created_at->format('m') }}/{{ $barangMasuk->created_at->format('Y') }}
        </p>
    </div>

    <!-- Informasi Umum -->
    <div class="info-section">
        <h3>INFORMASI PENERIMAAN</h3>

        <div class="info-row">
            <div class="info-label">Tanggal Masuk</div>
            <div class="info-value">: {{ $barangMasuk->created_at->format('d F Y') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Operator</div>
            <div class="info-value">: {{ $barangMasuk->operator->name }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Kategori</div>
            <div class="info-value">: {{ $barangMasuk->subKategori->kategori->kode }} -
                {{ $barangMasuk->subKategori->kategori->nama }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Sub Kategori</div>
            <div class="info-value">: {{ $barangMasuk->subKategori->nama }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Asal Barang</div>
            <div class="info-value">: {{ $barangMasuk->asal_barang }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Nomor Surat</div>
            <div class="info-value">: {{ $barangMasuk->nomor_surat ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Status Verifikasi</div>
            <div class="info-value">:
                <span class="status-badge {{ $barangMasuk->is_verified ? 'status-verified' : 'status-pending' }}">
                    {{ $barangMasuk->is_verified ? 'TERVERIFIKASI' : 'BELUM TERVERIFIKASI' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Daftar Barang -->
    <h3>DAFTAR BARANG</h3>
    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Barang</th>
                <th width="15%">Harga Satuan</th>
                <th width="8%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="15%">Total Harga</th>
                <th width="12%">Tgl Expired</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangMasuk->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-right">{{ $item->harga_format }}</td>
                    <td class="text-center">{{ number_format($item->jumlah) }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-right">{{ $item->total_format }}</td>
                    <td class="text-center">
                        {{ $item->tgl_expired ? $item->tgl_expired->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        @php
            $batasHarga = $barangMasuk->subKategori->batas_harga;
            $totalHarga = $barangMasuk->total_harga;
            $sisaBudget = $batasHarga - $totalHarga;
        @endphp

        <div class="total-row">
            <span>Batas Budget:</span>
            <span>{{ $batasHarga }}</span>
        </div>

        <div class="total-row grand-total">
            <span>Total Harga:</span>
            <span>{{ $barangMasuk->total_harga_format }}</span>
        </div>

        <div class="total-row">
            <span>{{ $sisaBudget >= 0 ? 'Sisa Budget:' : 'Over Budget:' }}</span>
            <span style="color: {{ $sisaBudget >= 0 ? 'green' : 'red' }}">
                {{ abs($sisaBudget) }}
            </span>
        </div>
    </div>

    <div style="clear: both;"></div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Diterima Oleh:</p>
            <div class="signature-line"></div>
            <p><strong>{{ $barangMasuk->operator->name }}</strong></p>
            <p>Operator</p>
        </div>

        <div class="signature-box">
            <p>Mengetahui:</p>
            <div class="signature-line"></div>
            <p><strong>_____________________</strong></p>
            <p>Kepala Bagian</p>
        </div>

        <div class="signature-box">
            <p>Menyetujui:</p>
            <div class="signature-line"></div>
            <p><strong>_____________________</strong></p>
            <p>Manager</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak pada {{ now()->format('d F Y H:i:s') }}</p>
        <p>Sistem Pengelolaan Barang - {{ config('app.name') }}</p>
    </div>
</body>

</html>
