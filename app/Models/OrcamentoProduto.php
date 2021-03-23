<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoProduto extends Model
{
    protected $table = 'orcamento_produto';
    protected $guarded = [];

    /**
     * Get the produto that owns the OrcamentoProduto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
