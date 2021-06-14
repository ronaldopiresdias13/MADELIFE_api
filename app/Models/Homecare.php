<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homecare extends Model
{
    protected $guarded = [];

    public function telefones()
    {
        return $this->belongsToMany(Telefone::class, 'homecare_telefone')->withPivot('id', 'tipo', 'descricao');
    }

    public function emails()
    {
        return $this->belongsToMany(Email::class, 'homecare_email')->withPivot('id', 'tipo', 'descricao');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }
}
