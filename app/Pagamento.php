<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $guarded = [];

    public function conta()
    {
        return $this->belongsTo('App\Conta');
    }
}
