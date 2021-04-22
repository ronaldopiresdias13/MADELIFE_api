<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrcProduto extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'orc_produto';
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
        'produto_id',
        'quantidade',
        'valorunitario',
        'subtotal',
        'custo',
        'subtotalcusto',
        'valorresultadomensal',
        'valorcustomensal',
        'locacao',
        'descricao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the produto that owns the OrcProduto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Get the orc that owns the OrcProduto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orc(): BelongsTo
    {
        return $this->belongsTo(Orc::class);
    }
}
