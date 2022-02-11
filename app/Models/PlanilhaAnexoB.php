<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanilhaAnexoB extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'planilhas_anexo_b';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id', 'paciente_id', 
        'data_avaliacao',
        'cpatient_id'

    ];

    public function cpaciente()
    {
        return $this->belongsTo(ClientPatient::class, 'cpatient_id', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id');
    }


    public function opcoes()
    {
        return $this->hasMany(OpcoesAnexoB::class, 'anexo_b_id', 'id');
    }

    public function informacoes()
    {
        return $this->hasMany(AnexoBInformacoes::class, 'anexo_b_id', 'id');
    }

   

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
    
    public function servicos()
    {
        return $this->belongsToMany(ServicoSocioAmbiental::class, 'servicos_and_socios_ambiental', 'anexo_b_id', 'servico_id');
    }
}
