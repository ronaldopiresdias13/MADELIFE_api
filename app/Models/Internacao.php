<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internacao extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'internacoes';
    protected $guarded = [];
    protected $keytype = 'string';
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at'
    ];
    protected $fillable = [
        'id',
        'paciente_id',
        'data_inicio',
        'data_final',
        'created_at',
        'update_at',
        'deleted_at'
    ];
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
