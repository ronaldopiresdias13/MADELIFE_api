<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    protected $table = 'profissionais';
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
    
    public function setor()
    {
        return $this->belongsTo('App\Setor');
    }
    
    public function cargo()
    {
        return $this->belongsTo('App\Cargo');
    }
    
    public function dadocontratual()
    {
        return $this->belongsTo('App\Dadoscontratual');
    }
    
    public function formacoes()
    {
        return $this->belongsToMany('App\Formacao', 'profissional_formacao');
    }
}
