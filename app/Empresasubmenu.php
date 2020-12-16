<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresasubmenu extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'empresamenu_id',
        'nome',
        'icone',
        'rota',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $keyType = 'string';
    public $incrementing = false;
}
