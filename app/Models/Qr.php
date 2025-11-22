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
        'dibuat_pada',
        'is_active',
    ];

    protected $appends = ['payload'];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Payload yang disematkan pada QR berisi meta penting.
     */
    public function getPayloadAttribute(): string
    {
        $peminjaman = $this->relationLoaded('peminjaman') ? $this->peminjaman : $this->peminjaman()->first();

        return json_encode([
            'type'           => $this->jenis_transaksi,
            'kode_transaksi' => $this->qr_code,
            'id_peminjaman'  => $this->id_peminjaman,
            'id_mahasiswa'   => $peminjaman?->id_pengguna,
            'id_barang'      => $peminjaman?->id_barang,
        ]);
    }
}
