<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class CuidadoPaciente extends Model
{
    use Uuid;

    protected $table = 'cuidado_paciente';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    public function formacao()
    {
        return $this->belongsTo(Formacao::class);
    }
    public function cuidado()
    {
        return $this->belongsTo(Cuidado::class);
    }
}
