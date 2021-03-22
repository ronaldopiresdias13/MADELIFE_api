<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversaMensagem extends Model
{
    use HasFactory;


    protected $table = 'conversas_mensagens';

    protected $fillable = [
        'conversa_id', 'sender_id','type','message','arquivo','uuid','visto'
    ];

    protected $guarded = [];

    public function conversa()
    {
        return $this->belongsTo(Conversa::class,'conversa_id','id');
    }

    public function sender()
    {
        return $this->belongsTo(Pessoa::class,'sender_id','id');
    }
}
