<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    // use Uuid;

    // protected $keyType = 'string';
    // protected $primaryKey = 'uuid';
    protected $guarded = [];
    // protected $hidden = [
    //     'id'
    // ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'sala_id', 'id')->where('ativo', true);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
