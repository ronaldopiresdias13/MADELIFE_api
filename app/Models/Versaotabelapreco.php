<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Versaotabelapreco extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Get all of the itens for the Versaotabelapreco
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itens(): HasMany
    {
        return $this->hasMany(Itemtabelapreco::class);
    }

    /**
     * Get the tabela that owns the Versaotabelapreco
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tabela(): BelongsTo
    {
        return $this->belongsTo(Tabelapreco::class);
    }
}
