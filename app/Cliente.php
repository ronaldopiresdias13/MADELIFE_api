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
    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'empresa_id');
    }
    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa', 'pessoa_id');
    }
}
