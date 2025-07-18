<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangMasukExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = BarangMasuk::with(['operator', 'subKategori.kategori', 'items']);

        // Apply same filters as in controller
        if (auth()->user()->role === 'operator') {
            $query->where('operator_id', auth()->id());
        }

        if (isset($this->filters['kategori_id']) && $this->filters['kategori_id']) {
            $query->whereHas('subKategori', function ($q) {
                $q->where('kategori_id', $this->filters['kategori_id']);
            });
        }

        if (isset($this->filters['sub_kategori_id']) && $this->filters['sub_kategori_id']) {
            $query->where('sub_kategori_id', $this->filters['sub_kategori_id']);
        }

        if (isset($this->filters['tahun']) && $this->filters['tahun']) {
            $query->whereYear('created_at', $this->filters['tahun']);
        }

        if (isset($this->filters['search']) && $this->filters['search']) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('asal_barang', 'like', "%{$search}%")
                    ->orWhereHas('subKategori', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Masuk',
            'Operator',
            'Kategori',
            'Sub Kategori',
            'Asal Barang',
            'Nomor Surat',
            'Total Harga',
            'Status Verifikasi',
            'Items'
        ];
    }

    public function map($barangMasuk): array
    {
        $items = $barangMasuk->items->map(function ($item) {
            return $item->nama_barang . ' (' . $item->jumlah . ' ' . $item->satuan . ')';
        })->implode(', ');

        return [
            $barangMasuk->id,
            $barangMasuk->created_at->format('d/m/Y'),
            $barangMasuk->operator->name,
            $barangMasuk->subKategori->kategori->nama,
            $barangMasuk->subKategori->nama,
            $barangMasuk->asal_barang,
            $barangMasuk->nomor_surat ?: '-',
            'Rp ' . number_format($barangMasuk->total_harga, 2, ',', '.'),
            $barangMasuk->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi',
            $items
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
