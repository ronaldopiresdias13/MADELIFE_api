<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itemtabelapreco extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'versaotabelapreco_id',
        'codigo',
        'tiss',
        'tuss',
        'nome',
        'preco'
    ];

    /**
     * Get the versao that owns the Itemtabelapreco
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function versao(): BelongsTo
    {
        return $this->belongsTo(Versaotabelapreco::class);
    }
}
