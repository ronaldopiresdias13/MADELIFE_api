<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tabelapreco extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'empresa_id',
        'nome',
        'padrao'
    ];

    /**
     * Get all of the versoes for the Tabelapreco
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versoes(): HasMany
    {
        return $this->hasMany(Versaotabelapreco::class);
    }
}
