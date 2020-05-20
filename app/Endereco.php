<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $guarded = [];

    public function cidade(){
        return $this->belongsTo('App\Cidade');
    }
}
