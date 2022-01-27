<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
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
        'orcamento_id',
        'realizada',
        'data',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    // protected $guarded = [];

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }
    public function vendasaida()
    {
        return $this->belongsTo(VendaSaida::class);
    }
}
