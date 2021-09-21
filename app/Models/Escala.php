<?php

namespace App\Models;

use App\Traits\TracksHistoryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Escala extends Model
{
    use TracksHistoryTrait;

    protected $guarded = [];

    public function cuidados()
    {
        return $this->belongsToMany(Cuidado::class, 'cuidado_escalas')
            ->withPivot(
                'id',
                'data',
                'hora',
                'status'
            )->wherePivot('ativo', true);
    }

    public function pontos()
    {
        return $this->hasMany(Ponto::class)->where('ativo', true);
    }

    public function relatorios()
    {
        return $this->hasMany(Relatorio::class)->where('ativo', true);
    }

    public function monitoramentos()
    {
        return $this->hasMany(Monitoramentoescala::class); //->where('ativo', true);
    }

    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    public function acaoMedicamentos()
    {
        return $this->hasMany(Acaomedicamento::class)->where('ativo', true);
    }

    public function formacao()
    {
        return $this->belongsTo(Formacao::class);
    }

    public function relatorioescalas()
    {
        return $this->hasMany(Relatorioescala::class)->where('ativo', true);
    }

    // public function ocorrencias()
    // {
    //     return $this->hasMany(Ocorrencia::class,'escala_id','id');
    // }

    public function ocorrencias()
    {
        return $this->belongsToMany(Ocorrencia::class, 'ocorrencias_escalas','escala_id', 'ocorrencia_id');
    }

    /**
     * Get the folga associated with the Escala
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function folga(): HasOne
    {
        return $this->hasOne(Folga::class);
    }
}
