<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patrimonio extends Model
{
    protected $guarded = [];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
