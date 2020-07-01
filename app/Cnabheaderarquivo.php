<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cnabheaderarquivo extends Model
{
    protected $guarded = [];

    public function cnabheaderlotes()
    {
        return $this->belongsToMany('App\Cnabheaderlote', 'cnabheaderarquivoheaderlotes');
    }

    public function cnabtrailerlotes()
    {
        return $this->belongsToMany('App\Cnabtrailerlote', 'cnabheaderarquivotrailerlotes');
    }
}
