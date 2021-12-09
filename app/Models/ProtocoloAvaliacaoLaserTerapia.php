<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloAvaliacaoLaserTerapia extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'avaliacaolaserterapia';
    protected $guarded = [];

    public function protocolodiagnosticos()
    {
        return $this->belongsTo(ProtocoloDiagnosticoFerida::class, 'pro_diagnostico_id');
    }
}
