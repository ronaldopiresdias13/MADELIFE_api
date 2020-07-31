<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Sala extends Model
{
    // use Uuid;

    protected $guarded = [];

    public function agendamentos()
    {
        return $this->hasMany('App\Agendamento')->where('ativo', true);
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }
}
