<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    protected $table = 'responsaveis';
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
    public function pacientes()
    {
        return $this->hasMany(Paciente::class)->where('ativo', true);
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
