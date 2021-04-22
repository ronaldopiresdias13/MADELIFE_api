<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'empresa_id',
        'paciente_id',
        'nome',
        'caminho',
        'mes',
        'ano',
        'categoria_id',
        'status',
        'observacao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function categoria()
    {
        return $this->belongsTo(Categoriadocumento::class);
    }
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
