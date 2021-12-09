<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloAvaliacaoFerida extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'protocolo_avaliacao_ferida';
    protected $guarded = [];

    public function protocolo()
    {
        return $this->belongsTo(ProtocoloSkin::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function protocololesao()
    {
        return $this->belongsTo(ProtocoloAvaliacaoLesaoPressao::class);
    }

    public function protocolopesdiabeticos()
    {
        return $this->belongsTo(ProtocoloAvaliacaoPesDiabetico::class);
    }

    public function protocololaserterapia()
    {
        return $this->belongsTo(ProtocoloAvaliacaoLaserTerapia::class);
    }
}
