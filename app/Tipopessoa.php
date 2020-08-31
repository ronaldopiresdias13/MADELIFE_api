<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipopessoa extends Model
{
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
}
