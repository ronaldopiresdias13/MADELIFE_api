<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntradaProduto extends Model
{
    // use Uuid;

    // protected $keyType = 'string';
    // protected $primaryKey = 'uuid';
    protected $table = 'entrada_produto';
    protected $guarded = [];
}
