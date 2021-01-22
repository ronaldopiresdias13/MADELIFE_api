<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cnabsantander extends Model
{
    protected $guarded = [];

    public function cnabheaderarquivo()
    {
        return $this->belongsTo(Cnabheaderarquivo::class);
    }

    public function cnabtrailerarquivo()
    {
        return $this->belongsTo(Cnabtrailerarquivo::class);
    }
}
