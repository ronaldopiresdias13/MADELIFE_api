<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cnab extends Model
{
    protected $guarded = [];

    public function cnabsantanders()
    {
        return $this->hasMany('App\Cnabsantander');
    }

    public function teste()
    {
        return $this->hasMany('App\Cnabsantander');
    }
}
