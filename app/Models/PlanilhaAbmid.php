<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanilhaAbmid extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'planilhas_abmids';
    public $incrementing = false;

    protected $fillable = [
        'diagnostico_principal_id','empresa_id', 'paciente_id', 'classificacao','data_avaliacao'
        
       
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id');
    }

    // public function diagnostico_principal()
    // {
    //     return $this->belongsTo(DiagnosticoPil::class, 'diagnostico_principal_id', 'id');
    // }

    public function diagnosticos_secundarios()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'abmids_ds', 'abmid_id', 'diagnostico_secundario_id');
    }

    public function itens()
    {
        return $this->hasMany(ItemAbmid::class, 'abmid_id', 'id');
    }

   

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    public function diagnosticos_principais()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'abmid_diagnosticos_principais', 'abmid_id', 'diagnostico_principal_id');
    }
}
