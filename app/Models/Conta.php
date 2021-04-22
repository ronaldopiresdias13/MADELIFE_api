<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    protected $guarded = [];

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class)->where('ativo', true);
    }
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
