<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    use HasFactory;

    protected $table = 'chamados';

    protected $fillable = [
        'prestador_id','criador_id','assunto','mensagem_inicial','finalizado','justificativa','protocolo','tipo','empresa_id'
    ];

    protected $casts = [
        'finalizado' => 'boolean'
    ];
    public function criador()
    {
        return $this->belongsTo(Pessoa::class, 'criador_id', 'id');
    }
   
    public function prestador()
    {
        return $this->belongsTo(Pessoa::class, 'prestador_id', 'id');
    }

    
    public function mensagens()
    {
        return $this->hasMany(MensagemChamado::class, 'chamado_id', 'id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
