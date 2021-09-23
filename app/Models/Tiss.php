<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tiss extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'tiss';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'cliente_id',
        'sequencia',
        'data',
        'hora',
        'nomexml',
        'caminhoxml',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the cliente that owns the Tiss
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get all of the medicoes for the Tiss
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicoes(): HasMany
    {
        return $this->hasMany(Medicao::class);
    }
}
