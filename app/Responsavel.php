<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    protected $table = 'responsaveis';
    protected $guarded = [];
    
    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
}
