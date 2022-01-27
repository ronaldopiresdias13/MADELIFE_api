<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloAvaliacaoLesaoPressao extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'protocolo_avaliacao_lesao_pressao';
    protected $guarded = [];
}
