<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupocuidado extends Model
{
    protected $guarded = [];

    public function cuidados()
    {
        // return $this->belongsToMany('App\Cuidado', 'cuidado_grupocuidados', 'grupocuidado_id', 'cuidado_id'); // , 'role_user_table', 'user_id', 'role_id'
        return $this->belongsToMany(Cuidado::class)->withPivot(
            "id",
            "cuidado_id",
            "grupocuidado_id",
        )->wherePivot('ativo', true); // , 'role_user_table', 'user_id', 'role_id'
    }
}
