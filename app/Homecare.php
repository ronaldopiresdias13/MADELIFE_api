<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homecare extends Model
{
    protected $guarded = [];

    public function telefones()
    {
        return $this->belongsToMany('App\Telefone', 'homecare_telefone');
    }

    public function emails()
    {
        return $this->belongsToMany('App\Email', 'homecare_email');
    }
}
