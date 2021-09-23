<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaDados extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'empresas_dados';

    protected $fillable = [
        'empresa_id', 'codigo', 'agencia','digito_agencia','conta','digito_conta','convenio','convenio_externo','nome','nome_empresa','cnpj'
    ];

    protected $guarded = [];

  

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
}
