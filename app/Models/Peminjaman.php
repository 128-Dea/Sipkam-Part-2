<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $primaryKey = 'id_peminjaman';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'id_barang',
        'waktu_awal',
        'waktu_akhir',
        'alasan',
        'status',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function keluhan(): HasMany
    {
        return $this->hasMany(Keluhan::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function perpanjangan(): HasMany
    {
        return $this->hasMany(Perpanjangan::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function qr(): HasOne
    {
        return $this->hasOne(Qr::class, 'id_peminjaman', 'id_peminjaman');
    }
}
