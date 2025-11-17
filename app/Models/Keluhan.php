<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Keluhan extends Model
{
    protected $table = 'keluhan';

    protected $primaryKey = 'id_keluhan';

    public $timestamps = false;

    protected $fillable = [
        'keluhan',
        'id_pengguna',
        'id_peminjaman',
        'foto_path',
    ];

    protected $appends = [
        'foto_url',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id_keluhan', 'id_keluhan');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_path
            ? asset('storage/' . ltrim($this->foto_path, '/'))
            : null;
    }
}
