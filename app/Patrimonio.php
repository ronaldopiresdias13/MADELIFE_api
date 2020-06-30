<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patrimonio extends Model
{
    protected $guarded = [];

    public function produto()
    {
        return $this->belongsTo('App\Produto');
    }
}
