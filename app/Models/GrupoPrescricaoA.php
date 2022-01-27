<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoPrescricaoA extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'grupos_a';
    public $incrementing = false;

    protected $fillable = [
        'nome','empresa_id'
    ];

    public function prescricoes()
    {
        return $this->belongsToMany(PrescricaoA::class, 'grupos_prescricoes_a','grupo_id', 'prescricao_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
