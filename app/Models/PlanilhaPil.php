<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanilhaPil extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'planilhas_pils';
    public $incrementing = false;

    protected $fillable = [
        'diagnostico_primario_id','empresa_id', 'paciente_id', 
        'revisao', 
        
        'prognostico', 
        'avaliacao_prescricoes', 
        'justificativa_revisao', 
        'evolucao_base',
        'referencia',
        'cpatient_id',
        'desenvolvido_por',
        'desenvolvido_por_data',
        'atualizado_por',
        'atualizado_por_data',
        'aprovado_por',
        'aprovado_por_data'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id');
    }

    public function cpaciente()
    {
        return $this->belongsTo(ClientPatient::class, 'cpatient_id', 'id');
    }

    // public function diagnostico_primario()
    // {
    //     return $this->belongsTo(DiagnosticoPil::class, 'diagnostico_primario_id', 'id');
    // }

    public function diagnosticos_principais()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'pil_diagnosticos_principais', 'pil_id', 'diagnostico_principal_id');
    }

    public function diagnosticos_secundarios()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'diagnosticos_secundarios_pil', 'pil_id', 'diagnostico_secundario_id');
    }

    public function prescricoes_a()
    {
        return $this->belongsToMany(PrescricaoA::class, 'prescricoes_a_pil', 'pil_id', 'prescricao_id');
    }

    public function prescricoes_b()
    {
        return $this->hasMany(PrescricaoBPil::class, 'pil_id', 'id');
    }

    public function medicamentos()
    {
        return $this->hasMany(MedicamentoPil::class, 'pil_id', 'id');
    }

    public function horarios_medicamentos()
    {
        return $this->hasMany(HorarioMedicamentoPil::class, 'pil_id', 'id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    
}
