<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SerahTerima extends Model
{
    protected $table = 'serah_terima';

    protected $primaryKey = 'id_serah_terima';

    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'pengguna_lama',
        'pengguna_baru',
        'waktu',
        'catatan',
        'status_persetujuan',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function penggunaLama(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_lama', 'id_pengguna');
    }

    public function penggunaBaru(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_baru', 'id_pengguna');
    }

    public function qr(): HasOne
    {
        return $this->hasOne(Qr::class, 'id_serah_terima', 'id_serah_terima');
    }
}
