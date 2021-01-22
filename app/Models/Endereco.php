<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $guarded = [];

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }
}
