<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Historico extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'pessoa_id',
        'tipo', // 1-Store, 2-Update, 3-Delete
        'model',
        'itens',
        'created_at',
        'updated_at'
    ];
}
