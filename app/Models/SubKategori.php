<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'nama',
        'batas_harga',
    ];

    protected $table = 'sub_kategoris';

    protected $casts = [
        'batas_harga' => 'decimal:2',
    ];

    /**
     * Relasi ke Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Accessor untuk format rupiah
     */
    public function getBatasHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->batas_harga, 2, ',', '.');
    }
}
