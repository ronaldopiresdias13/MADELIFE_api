<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CnabPessoa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cnabpessoas';

    protected $fillable = [
        'cnab_id', 'pessoa_id', 'valor','agencia','conta','digito','banco','status'
    ];

    protected $guarded = [];

    public function cnab()
    {
        return $this->belongsTo(RegistroCnab::class,'cnab_id','id');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class,'pessoa_id','id');
    }
}
