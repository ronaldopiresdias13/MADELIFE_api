<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = 'fornecedores';
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
