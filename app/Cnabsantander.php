<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cnabsantander extends Model
{
    protected $guarded = [];

    public function cnabheaderarquivo()
    {
        return $this->belongsTo('App\Cnabheaderarquivo');
    }

    public function cnabtrailerarquivo()
    {
        return $this->belongsTo('App\Cnabtrailerarquivo');
    }
}
