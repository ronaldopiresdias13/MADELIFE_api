<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrcServico extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'orc_servico';
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
        'servico_id',
        'quantidade',
        'basecobranca',
        'frequencia',
        'valorunitario',
        'subtotal',
        'custo',
        'custodiurno',
        'horascuidadodiurno',
        'custonoturno',
        'horascuidadonoturno',
        'subtotalcusto',
        'valorresultadomensal',
        'valorcustomensal',
        'horascuidadodiurno',
        'horascuidadonoturno',
        'icms',
        'iss',
        'inss',
        'descricao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the servico that owns the OrcServico
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }
}
