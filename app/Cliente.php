<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $guarded = [];

    // public function pessoas()
    // {
    //     return $this->belongsTo('App\Pessoa', 'id', 'cliente');
    // }
    // public function empresa()
    // {
    //     return $this->belongsTo('App\Empresa');
    // }
    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
}
