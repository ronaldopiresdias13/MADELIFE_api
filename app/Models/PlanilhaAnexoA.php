<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanilhaAnexoA extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'planilhas_anexo_a';
    public $incrementing = false;

    protected $fillable = [
        'diagnostico_principal_id','empresa_id', 'paciente_id', 'classificacao_escala_braden','classificacao_coma_glasgow',
        'intensidade_dor','diametros_pupilas','data_avaliacao'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id');
    }

    public function diagnostico_principal()
    {
        return $this->belongsTo(DiagnosticoPil::class, 'diagnostico_principal_id', 'id');
    }

    public function diagnosticos_secundarios()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'anexoa_ds', 'anexo_a_id', 'diagnostico_secundario_id');
    }

    public function escalas_braden()
    {
        return $this->hasMany(EscalaBradenAnexoA::class, 'anexo_a_id', 'id');
    }

    public function escalas_coma_glasgow()
    {
        return $this->hasMany(EscalaComaGlasgowAnexoA::class, 'anexo_a_id', 'id');
    }

    public function exames_fisicos()
    {
        return $this->hasMany(ExameFisicoAnexoA::class, 'anexo_a_id', 'id');
    }

   

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
