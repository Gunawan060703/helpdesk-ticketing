<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMasalah extends Model
{
    use HasFactory;

    protected $table = 'tabel_kategori_masalah';
    protected $primaryKey = 'id_kategori';
    public $timestamps = true;

    protected $fillable = ['nama_kategori'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'kategori', 'nama_kategori');
    }
}