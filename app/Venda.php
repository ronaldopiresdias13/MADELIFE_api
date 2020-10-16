<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use Uuid;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function orcamento()
    {
        return $this->belongsTo('App\Orcamento');
    }
}
