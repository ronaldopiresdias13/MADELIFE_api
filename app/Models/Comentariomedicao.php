<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comentariomedicao extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = "comentariosmedicao";
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'id',
        'medicoes_id',
        'pessoa_id',
        'comentario',
        'data',
        'hora',
        'situacao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $keyType = 'string';
    public $incrementing = false;

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
