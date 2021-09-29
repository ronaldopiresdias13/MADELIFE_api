<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacao extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'notificacoes';

    protected $guarded = [];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
