<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'alamat',
        'nomor_telepon',
        'tanggal_bergabung',
        'peran',
    ];

    protected $hidden = [
        'kata_sandi',
    ];

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'id_pengguna', 'id');
    }
}
