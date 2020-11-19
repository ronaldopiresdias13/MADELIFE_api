<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificadoprestador extends Model
{
    protected $table = 'certificadoprestadores';
    protected $guarded = [];

    public function prestador()
    {
        return $this->belongsTo(Prestador::class);
    }
}
