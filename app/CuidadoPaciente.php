<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class CuidadoPaciente extends Model
{
    use Uuid;

    protected $table = 'cuidado_paciente';
    protected $guarded = [];

    public function formacao()
    {
        return $this->belongsTo('App\Formacao');
    }
    public function cuidado()
    {
        return $this->belongsTo('App\Cuidado');
    }
}
