<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tiss extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'tiss';

    protected $fillable = [
        'id',
        'sequencia',
        'datasolicitacao',
        'caminhoxml',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
