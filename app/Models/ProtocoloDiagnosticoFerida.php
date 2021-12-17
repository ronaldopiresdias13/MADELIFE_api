<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloDiagnosticoFerida extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'protocolo_diagnostico_ferida';
    protected $guarded = [];

    public function protocolocausa()
    {
        return $this->belongsTo(ProtocoloCausaFerida::class);
    }

    public function protocolosintomas()
    {
        return $this->belongsTo(ProtocoloSinaisSintomaFeridas::class);
    }

    public function anexos()
    {
        return $this->morphMany(Anexo::class, 'anexo');
    }
}
