<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensagemChamado extends Model
{
    use HasFactory;

    protected $table = 'mensagens_chamado';

    protected $fillable = [
        'atendente_id','prestador_id','message','uuid','visto','chamado_id','type','arquivo'
    ];
   
    public function atendente()
    {
        return $this->belongsTo(Pessoa::class, 'atendente_id', 'id');
    }

    public function prestador()
    {
        return $this->belongsTo(Pessoa::class, 'prestador_id', 'id');
    }
    public function chamado()
    {
        return $this->belongsTo(Chamado::class, 'chamado_id', 'id');
    }
}
