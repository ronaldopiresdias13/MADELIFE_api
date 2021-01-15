<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    // use Uuid;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'empresa_id',
        'profissional_id',
        'sala_id',
        'nome',
        'descricao',
        'cor',
        'datainicio',
        'datafim',
        'horainicio',
        'horafim',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // protected $keyType = 'string';
    // protected $primaryKey = 'uuid';
    // protected $guarded = [];

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
