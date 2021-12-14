<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloMedicacao extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'protocolo_medicacao';
    protected $guarded = [];

    public function protocolo()
    {
        return $this->belongsTo(ProtocoloSkin::class);
    }

    public function protocolo_avaliacao_medicamento()
    {
        return $this->belongsTo(ProtocoloAvaliacaoMedicamento::class, 'protocolo_avaliacao_medicacao_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
