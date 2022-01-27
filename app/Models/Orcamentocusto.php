<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orcamentocusto extends Model
{
    protected $table = 'orcamentocustos';
    protected $guarded = [];

    /**
     * Get the custo that owns the Orcamentocusto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function custo(): BelongsTo
    {
        return $this->belongsTo(Custo::class);
    }
}
