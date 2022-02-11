<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfissionalBeneficio extends Model
{
    protected $table = 'profissional_beneficio';
    protected $guarded = [];

    /**
     * Get the beneficio that owns the ProfissionalBeneficio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beneficio(): BelongsTo
    {
        return $this->belongsTo(Beneficio::class);
    }
}
