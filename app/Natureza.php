<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Natureza extends Model
{
    protected $guarded = [];

    public function categorianatureza()
    {
        return $this->belongsTo(Categorianatureza::class);
    }
}
