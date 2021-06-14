<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversa extends Model
{
    use HasFactory;

    protected $table = 'conversas';

    protected $fillable = [
        'sender_id', 'receive_id'
    ];

    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(Pessoa::class,'sender_id','id');
    }

    public function receive()
    {
        return $this->belongsTo(Pessoa::class,'receive_id','id');
    }

    public function mensagens()
    {
        return $this->hasMany(ConversaMensagem::class,'conversa_id','id');
    }
}
