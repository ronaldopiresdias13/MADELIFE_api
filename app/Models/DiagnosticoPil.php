<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiagnosticoPil extends Model
{
    use HasFactory, Uuid,SoftDeletes;

    protected $table = 'diagnosticos_pil';
    public $incrementing = false;

    protected $fillable = [
        'codigo','nome', 'descricao','referencias','flag'
    ];
}
