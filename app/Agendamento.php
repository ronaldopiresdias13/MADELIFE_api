<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use Uuid;

    protected $keyType = 'string';
    protected $primaryKey = 'uuid';
    protected $guarded = [];
    // protected $hidden = [
    //     'id'
    // ];

    public function sala()
    {
        return $this->belongsTo('App\Sala');
    }

    public function profissional()
    {
        return $this->belongsTo('App\Profissional');
    }
}
