<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagamentoexterno extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $keyType = 'string';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'id',
        'empresa_id',
        'pessoa_id',
        'servico_id',
        'datainicio',
        'datafim',
        'ordemservico_id',
        'quantidade',
        'turno',
        'valorunitario',
        'subtotal',
        'status',
        'observacao',
        'situacao',
        'proventos',
        'descontos',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
