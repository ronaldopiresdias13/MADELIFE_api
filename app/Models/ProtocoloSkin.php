<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProtocoloSkin extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'protocolo_skin';
    protected $guarded = [];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

}
