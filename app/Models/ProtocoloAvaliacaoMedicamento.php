<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloAvaliacaoMedicamento extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'protocolo_avaliacao_medicamento';
    protected $guarded = [];

    public function medicacao()
    {
        return $this->belongsTo(ProtocoloMedicacao::class);
    }
}
