<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    protected $guarded = [];

    /**
     * Get the cidade that owns the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }
}
