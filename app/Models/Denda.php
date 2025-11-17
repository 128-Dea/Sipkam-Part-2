<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    protected $table = 'denda';

    protected $primaryKey = 'id_denda';

    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'jenis',
        'total_denda',
        'status_pembayaran',
        'keterangan',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}
