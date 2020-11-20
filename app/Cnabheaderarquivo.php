<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cnabheaderarquivo extends Model
{
    protected $guarded = [];

    public function cnabheaderlotes()
    {
        return $this->belongsToMany(Cnabheaderlote::class, 'cnabheaderarquivoheaderlotes')->wherePivot('ativo', true);
    }

    public function cnabtrailerlotes()
    {
        return $this->belongsToMany(Cnabtrailerlote::class, 'cnabheaderarquivotrailerlotes')->wherePivot('ativo', true);
    }
}
