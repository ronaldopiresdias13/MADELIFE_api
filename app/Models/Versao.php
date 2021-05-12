<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Versao extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'versoes';
    protected $keytype = 'string';
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'plataforma',
        'versao',
        'created_at',
        'update_at',
        'deleted_at'
    ];
}
