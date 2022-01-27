<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aph extends Model
{
    protected $guarded = [];

    /**
     * Get the user that owns the Aph
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }
}
