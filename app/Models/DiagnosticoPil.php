<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PrescricoesBPil;

class DiagnosticoPil extends Model
{
    use HasFactory, Uuid,SoftDeletes;

    protected $table = 'diagnosticos_pil';
    public $incrementing = false;

    protected $fillable = [
        'codigo','nome', 'descricao','referencias','flag'
    ];

    public function planilhas_diagnostico_primario(){
        return $this->hasMany(PlanilhaPil::class,'diagnostico_primario_id','id');
    }


    public function planilhas_diagnosticos_secundarios()
    {
        return $this->belongsToMany(PlanilhaPil::class, 'diagnosticos_secundarios_pil',  'diagnostico_secundario_id','pil_id');
    }

    public function prescricoes_b()
    {
        return $this->belongsToMany(PrescricoesBPil::class, 'prescricoes_b_diag_sec_pil','diagnostico_secundario_id','prescricao_id');
    }

    public function neads_diagnosticos_secundarios()
    {
        return $this->belongsToMany(Nead::class, 'neads_diagnosticos_secundarios', 'diagnostico_secundario_id','nead_id');
    }

    public function abmids_diagnosticos_secundarios()
    {
        return $this->belongsToMany(PlanilhaAbmid::class, 'abmids_ds', 'diagnostico_secundario_id','abmid_id');
    }

    public function anexoa_diagnosticos_secundarios()
    {
        return $this->belongsToMany(PlanilhaAnexoA::class, 'anexoa_ds', 'diagnostico_secundario_id','anexo_a_id');
    }
}
