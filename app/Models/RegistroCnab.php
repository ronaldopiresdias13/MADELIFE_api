<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroCnab extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'registrocnabs';

    protected $fillable = [
        'empresa_id', 'arquivo', 'mes','codigo_banco','data','observacao','situacao'
    ];

    protected $guarded = [];

    public function pagamentos()
    {
        return $this->hasMany(CnabPessoa::class,'cnab_id','id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
}
