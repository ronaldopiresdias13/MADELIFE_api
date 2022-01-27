<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloAvaliacaoEstoma extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'protocolo_avaliacao_estoma';
    protected $guarded = [];
    
    public function protocolo()
    {
        return $this->belongsTo(ProtocoloSkin::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
