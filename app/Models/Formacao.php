<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formacao extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'formacoes';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // protected $guarded = [];
    protected $fillable = [
        'id',
        'descricao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
