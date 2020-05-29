<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    protected $table = 'profissionais';
    protected $guarded = [];

    public function formacoes()
    {
        return $this->belongsToMany('App\Formacao', 'profissional_formacao');
    }
}
