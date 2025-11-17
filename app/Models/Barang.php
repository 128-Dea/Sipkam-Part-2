<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id_barang';

    public $timestamps = false;

    protected $fillable = [
        'id_kategori',
        'nama_barang',
        'status',
        'kode_barang',
        'harga',
        'foto_path',
    ];

    protected $appends = [
        'foto_url',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_barang', 'id_barang');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_barang', 'id_barang');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_path
            ? asset('storage/' . ltrim($this->foto_path, '/'))
            : null;
    }
}
