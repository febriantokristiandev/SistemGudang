<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori', 'nama_kategori');
    }
}