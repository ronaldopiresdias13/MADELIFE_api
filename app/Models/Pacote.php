<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pacote extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $keyType = 'string';
    public $incrementing = false;


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'empresa_id',
        'descricao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function servicos()
    {
        return $this->hasMany(Pacoteservico::class, 'pacote_id');
    }
    public function produtos()
    {
        return $this->hasMany(Pacoteproduto::class, 'pacote_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
