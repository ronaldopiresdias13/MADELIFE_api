<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpresaPrestador extends Model
{
    protected $table = 'empresa_prestador';
    protected $guarded = [];

    public function prestador()
    {
        return $this->belongsTo("App\Prestador");
    }

    public function empresa()
    {
        return $this->belongsTo("App\Empresa");
    }
}
