<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'sub_kategori_id',
        'asal_barang',
        'nomor_surat',
        'lampiran',
        'total_harga',
        'is_verified',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    /**
     * Relasi ke User (Operator)
     */
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Relasi ke Sub Kategori
     */
    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }

    /**
     * Relasi ke Kategori via Sub Kategori
     */
    public function kategori()
    {
        return $this->hasOneThrough(Kategori::class, SubKategori::class, 'id', 'id', 'sub_kategori_id', 'kategori_id');
    }

    /**
     * Relasi ke Items
     */
    public function items()
    {
        return $this->hasMany(BarangMasukItem::class);
    }

    /**
     * Accessor untuk format total harga
     */
    public function getTotalHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 2, ',', '.');
    }

    /**
     * Accessor untuk tanggal masuk
     */
    public function getTanggalMasukAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }
}
