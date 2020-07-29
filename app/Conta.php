<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    protected $guarded = [];

    public function pagamentos()
    {
        return $this->hasMany('App\Pagamento')->where('ativo', true);
    }
    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
}
