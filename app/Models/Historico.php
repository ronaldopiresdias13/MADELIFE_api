<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Historico extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        // 'pessoa_id',
        // 'model',
        // 'itens',
        'id',
        'tipo', // 1-Create, 2-Update, 3-Delete
        'historico_type',
        'historico_id',
        'user_id',
        'body',
        'created_at',
        'updated_at'
    ];
}
