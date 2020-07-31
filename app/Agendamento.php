<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Agendamento extends Model
{
    // use Uuid;

    protected $guarded = [];
    
    public function sala()
    {
        return $this->belongsTo('App\Sala');
    }

    public function profissional()
    {
        return $this->belongsTo('App\Profissional');
    }
}
