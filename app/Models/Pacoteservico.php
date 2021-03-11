<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pacoteservico extends Model
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
        'pacote_id',
        'servico_id',
        'valor',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
