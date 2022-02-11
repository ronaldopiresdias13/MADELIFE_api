<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipopessoa extends Model
{
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
