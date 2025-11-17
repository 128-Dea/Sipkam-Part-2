<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perpanjangan extends Model
{
    protected $table = 'perpanjangan';

    protected $primaryKey = 'id_perpanjangan';

    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'alasan',
        'waktu_perpanjangan',
        'waktu_pengajuan',
        'status_persetujuan',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}
