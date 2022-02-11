<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ponto extends Model
{
    protected $guarded = [];

    /**
     * Get the escala that owns the Ponto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function escala(): BelongsTo
    {
        return $this->belongsTo(Escala::class);
    }
}
