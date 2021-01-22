<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use Uuid;

    protected $keyType = 'string';
    protected $primaryKey = 'uuid';
    protected $table = 'locacoes';
    protected $guarded = [];
}
