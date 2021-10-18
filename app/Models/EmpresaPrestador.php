<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaPrestador extends Model
{
    protected $table = 'empresa_prestador';
    protected $guarded = [];

    public function prestador()
    {
        return $this->belongsTo(Prestador::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function anexos()
    {
        return $this->morphMany(Anexo::class, 'anexo');
    }
}
