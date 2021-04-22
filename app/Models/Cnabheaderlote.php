<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cnabheaderlote extends Model
{
    protected $guarded = [];

    public function cnabdetalheas()
    {
        return $this->belongsToMany(Cnabdetalhea::class, 'cnabheaderlotedetalheas')->wherePivot('ativo', true);
    }

    public function cnabdetalhebs()
    {
        return $this->belongsToMany(Cnabdetalheb::class, 'cnabheaderlotedetalhebs')->wherePivot('ativo', true);
    }
}
