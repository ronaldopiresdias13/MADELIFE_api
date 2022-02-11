<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nead extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'neads';
    public $incrementing = false;

    protected $fillable = [
        'paciente_id',
        'pontuacao_final',
        'pontuacao_katz',
        'data_avaliacao',
        'empresa_id',
        'diagnostico_principal_id',
        'classificacaop_selecionado',
        'cpatient_id',
        'observacao_grupo1'
    ];

    public function cpaciente()
    {
        return $this->belongsTo(ClientPatient::class, 'cpatient_id', 'id');
    }
    // public function diagnostico_principal()
    // {
    //     return $this->belongsTo(DiagnosticoPil::class, 'diagnostico_principal_id', 'id');
    // }

    public function grupos1(){
        return $this->hasMany(NeadGrupo1::class,'neads_id','id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id');
    }

    public function grupos2(){
        return $this->hasMany(NeadGrupo2::class,'neads_id','id');
    }

    public function grupos3(){
        return $this->hasMany(NeadGrupo3::class,'neads_id','id');
    }

    public function katz(){
        return $this->hasMany(NeadKatz::class,'neads_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function diagnosticos_secundarios()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'neads_diagnosticos_secundarios', 'nead_id', 'diagnostico_secundario_id');
    }

    public function diagnosticos_principais()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'nead_diagnosticos_principais', 'nead_id', 'diagnostico_principal_id');
    }
}
