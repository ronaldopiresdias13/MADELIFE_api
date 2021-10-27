<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Custo extends Model
{
    use HasFactory;
    use Uuid;

    // protected $table = 'cuidado_paciente';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if (!$model->codigo) {
                $model->codigo = $model->where('empresa_id', Auth::user()->pessoa->profissional->empresa_id)->max('codigo') + 1;
            }
        });
    }
}
