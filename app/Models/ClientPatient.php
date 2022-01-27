<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientPatient extends Model
{
    use HasFactory, Uuid,SoftDeletes;

    protected $table = 'clients_patients';
    public $incrementing = false;

    protected $fillable = [
        'nome', 'sexo','cpf','rg','rua','numero','complemento','bairro','cidade', 'estado','latitude','longitude','nome_responsavel',
        'parentesco_responsavel','cpf_responsavel','telefone_responsavel','empresa_id','cep'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
}
