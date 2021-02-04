<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagamentointerno extends Model
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
        'datainicio',
        'datafim',
        'salario',
        'proventos',
        'descontos',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
