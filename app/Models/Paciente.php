<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paciente extends Model
{
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }

    public function cuidados()
    {
        return $this->belongsToMany(Cuidado::class, 'cuidado_paciente')->withPivot('id', 'formacao_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class); //->where('ativo', true);
    }

    public function homecares()
    {
        return $this->hasMany(Homecare::class)->where('ativo', true);
    }

    public function internacoes(){

        return $this->hasMany(Internacao::class);
    }

    /**
     * Get the empresa that owns the Paciente
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function planilhas(){
        return $this->hasMany(PlanilhaPil::class,'paciente_id','id');
    }
}
