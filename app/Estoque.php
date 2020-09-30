<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use Uuid;

    protected $keyType = 'string';
    protected $primaryKey = 'uuid';
    protected $guarded = [];
}
