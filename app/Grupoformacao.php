<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;

class Grupoformacao extends Model
{
    use Uuid;

    protected $table = 'grupoformacoes';
}
