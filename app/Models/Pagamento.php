<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $guarded = [];

    public function conta()
    {
        return $this->belongsTo(Conta::class);
    }
    public function contasbancaria()
    {
        return $this->belongsTo(Contasbancaria::class);
    }
}
