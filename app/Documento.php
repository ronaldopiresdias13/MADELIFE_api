<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use Uuid;

    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function categoria()
    {
        return $this->belongsTo('App\Categoriadocumento');
    }
    public function paciente()
    {
        return $this->belongsTo('App\Paciente');
    }
}
