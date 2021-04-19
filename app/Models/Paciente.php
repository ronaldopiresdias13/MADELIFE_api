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

    /**
     * Get the empresa that owns the Paciente
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
