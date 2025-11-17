<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $table = 'service';

    protected $primaryKey = 'id_service';

    public $timestamps = false;

    protected $fillable = [
        'id_keluhan',
        'status',
    ];

    public function keluhan(): BelongsTo
    {
        return $this->belongsTo(Keluhan::class, 'id_keluhan', 'id_keluhan');
    }
}
