<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ordemservico extends Model
{
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function transcricoes()
    {
        return $this->hasMany(Transcricao::class)->where('ativo', true);
    }

    public function escalas()
    {
        return $this->hasMany(Escala::class)->where('ativo', true);
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'ordemservico_servico')
            ->withPivot(
                'id',
                'descricao',
                'valordiurno',
                'valornoturno'
            )->wherePivot('ativo', true);
    }

    public function ordemservico_servicos()
    {
        return $this->belongsTo(OrdemservicoServico::class)->where('ativo', true);
    }

    public function prestadores()
    {
        return $this->belongsToMany(Prestador::class, 'ordemservico_prestador')
            ->wherePivot('ativo', true);
    }

    public function ordemservicoPrestador()
    {
        return $this->hasMany(OrdemservicoPrestador::class)->where('ativo', true);
    }

    public function acessos()
    {
        return $this->belongsToMany(Acesso::class, 'ordemservico_acessos')->withPivot('id', 'check');
    }

    protected static function booted()
    {
        static::created(function ($ordemservico) {
            $acessos = $ordemservico->empresa->acessos;

            foreach ($acessos as $key => $acesso) {
                DB::transaction(function () use ($ordemservico, $acesso) {
                    $ordemservicoAcesso = new OrdemservicoAcesso();
                    $ordemservicoAcesso->empresa_id      = $ordemservico->empresa_id;
                    $ordemservicoAcesso->ordemservico_id = $ordemservico->id;
                    $ordemservicoAcesso->acesso_id       = $acesso->id;
                    $ordemservicoAcesso->save();
                });
            }
        });
    }
}
