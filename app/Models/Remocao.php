<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remocao extends Model
{
    protected $table = 'remocoes';
    protected $guarded = [];

    /**
     * Get the cidadeorigem that owns the Remocao
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cidadeorigem(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'cidadeorigem');
    }

    /**
     * Get the cidadedestino that owns the Remocao
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cidadedestino(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'cidadedestino');
    }
}
