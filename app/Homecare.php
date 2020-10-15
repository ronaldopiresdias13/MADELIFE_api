<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homecare extends Model
{
    protected $guarded = [];

    public function telefones()
    {
        return $this->belongsToMany('App\Telefone', 'homecare_telefone')->withPivot('id', 'tipo', 'descricao');
    }

    public function emails()
    {
        return $this->belongsToMany('App\Email', 'homecare_email')->withPivot('id', 'tipo', 'descricao');
    }

    public function paciente()
    {
        return $this->belongsTo('App\Paciente');
    }
}
