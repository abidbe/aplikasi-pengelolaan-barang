<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_masuk_id',
        'nama_barang',
        'harga',
        'jumlah',
        'satuan',
        'total',
        'tgl_expired',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
        'tgl_expired' => 'date',
    ];

    /**
     * Relasi ke Barang Masuk
     */
    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    /**
     * Accessor untuk format harga
     */
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 2, ',', '.');
    }

    /**
     * Accessor untuk format total
     */
    public function getTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->total, 2, ',', '.');
    }
}
