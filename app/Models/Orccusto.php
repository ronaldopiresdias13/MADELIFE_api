<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orccusto extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'orc_id',
        'descricao',
        'quantidade',
        'unidade',
        'valorunitario',
        'valortotal',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the orc that owns the Orccusto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orc(): BelongsTo
    {
        return $this->belongsTo(Orc::class);
    }

    /**
     * Get the custo that owns the Orccusto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function custo(): BelongsTo
    {
        return $this->belongsTo(Custo::class);
    }
}
