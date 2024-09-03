<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_barang',
        'kode',
        'kategori',
        'lokasi',
        'stok_awal',
        'harga',
        'pemasok',
    ];

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'id_barang', 'id');
    }
}
