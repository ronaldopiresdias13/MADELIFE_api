<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cnab extends Model
{
    protected $guarded = [];

    public function cnabsantanders()
    {
        return $this->hasMany(Cnabsantander::class)->where('ativo', true);
    }
}
