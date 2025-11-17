<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qr extends Model
{
    protected $table = 'qr';

    protected $primaryKey = 'id_qr';

    public $timestamps = false;

    protected $fillable = [
        'qr_code',
        'jenis_transaksi',
        'id_peminjaman',
        'id_serah_terima',
        'dibuat_pada',
        'is_active',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function serahTerima(): BelongsTo
    {
        return $this->belongsTo(SerahTerima::class, 'id_serah_terima', 'id_serah_terima');
    }
}
