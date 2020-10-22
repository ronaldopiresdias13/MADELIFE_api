<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acaomedicamento extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    public function transcricaoProduto()
    {
        return $this->belongsTo('App\TranscricaoProduto');
    }
}
