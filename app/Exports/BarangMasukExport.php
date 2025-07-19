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
    protected $rowNumber = 1;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = BarangMasuk::with(['operator', 'subKategori.kategori', 'items']);

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

        $barangMasuks = $query->orderBy('created_at', 'desc')->get();

        $result = collect();

        foreach ($barangMasuks as $barangMasuk) {
            if ($barangMasuk->items->count() > 0) {
                foreach ($barangMasuk->items as $index => $item) {
                    $result->push((object)[
                        'barang_masuk' => $barangMasuk,
                        'item' => $item,
                        'item_index' => $index,
                        'is_first_item' => $index === 0,
                        'row_number' => $this->rowNumber
                    ]);
                }
            } else {
                // Jika tidak ada items
                $result->push((object)[
                    'barang_masuk' => $barangMasuk,
                    'item' => null,
                    'item_index' => 0,
                    'is_first_item' => true,
                    'row_number' => $this->rowNumber
                ]);
            }
            $this->rowNumber++;
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Asal Barang',
            'Penerima',
            'Unit',
            'Kode',
            'Nama',
            'Harga',
            'Jumlah',
            'Total',
            'Status'
        ];
    }

    public function map($row): array
    {
        $barangMasuk = $row->barang_masuk;
        $item = $row->item;

        return [
            $row->is_first_item ? $row->row_number : '', // No - hanya di baris pertama
            $row->is_first_item ? $barangMasuk->created_at->format('d/m/Y H:i') : '', // Tanggal
            $row->is_first_item ? $barangMasuk->asal_barang : '', // Asal Barang
            $row->is_first_item ? $barangMasuk->operator->name : '', // Penerima
            $row->is_first_item ? $barangMasuk->subKategori->nama : '', // Unit
            $item ? 'ITEM-' . str_pad($row->item_index + 1, 3, '0', STR_PAD_LEFT) : ($row->is_first_item ? 'Tidak ada items' : ''), // Kode
            $item ? $item->nama_barang : '', // Nama
            $item ? 'Rp ' . number_format($item->harga, 0, ',', '.') : '', // Harga
            $item ? $item->jumlah . ' ' . $item->satuan : '', // Jumlah
            $item ? 'Rp ' . number_format($item->total, 0, ',', '.') : '', // Total
            $row->is_first_item ? ($barangMasuk->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi') : '' // Status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row bold
        ];
    }
}
