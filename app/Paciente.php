<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }

    public function cuidados()
    {
        return $this->belongsToMany('App\Cuidado', 'cuidado_paciente')->withPivot('id', 'formacao_id');
    }

    public function documentos()
    {
        return $this->hasMany('App\Documento')->where('ativo', true);
    }
}
