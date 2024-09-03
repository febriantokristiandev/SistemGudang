<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'jenis_mutasi',
        'jumlah',
        'id_barang',
        'id_pengguna',
        'catatan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id');
    }
}
