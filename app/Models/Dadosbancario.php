<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dadosbancario extends Model
{
    protected $guarded = [];

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
}
