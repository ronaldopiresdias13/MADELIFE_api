<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrescricaoA extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'prescricoes_a';
    public $incrementing = false;

    protected $fillable = [
        'nome','empresa_id'
    ];

    public function grupos()
    {
        return $this->belongsToMany(GrupoPrescricaoA::class, 'grupos_prescricoes_a', 'prescricao_id', 'grupo_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
