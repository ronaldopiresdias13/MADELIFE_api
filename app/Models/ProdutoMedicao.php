<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoMedicao extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'medicoes_id',
        'produto_id',
        'quantidade',
        'atendido',
        'valor',
        'subtotal',
        'situacao',
        'observacao',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;
}
