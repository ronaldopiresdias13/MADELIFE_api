<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContratoTabelapreco extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
