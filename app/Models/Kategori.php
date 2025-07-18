<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
    ];

    protected $table = 'kategoris';

    /**
     * Relasi ke Sub Kategori
     */
    public function subKategoris()
    {
        return $this->hasMany(SubKategori::class);
    }
}
