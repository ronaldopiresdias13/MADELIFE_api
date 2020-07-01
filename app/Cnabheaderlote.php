<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cnabheaderlote extends Model
{
    protected $guarded = [];

    public function cnabdetalheas()
    {
        return $this->belongsToMany('App\Cnabdetalhea', 'cnabheaderlotedetalheas');
    }

    public function cnabdetalhebs()
    {
        return $this->belongsToMany('App\Cnabdetalheb', 'cnabheaderlotedetalhebs');
    }
}
