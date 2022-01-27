<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $guarded = [];

    // public function pessoas()
    // {
    //     return $this->belongsTo('App\Pessoa', 'id', 'cliente');
    // }
    // public function empresa()
    // {
    //     return $this->belongsTo('App\Empresa');
    // }
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // public function telefones()
    // {
    //     return $this->belongsToMany('App\Telefone', 'pessoa_telefone');
    // }

    // public function enderecos()
    // {
    //     return $this->morphToMany('App\Endereco', 'pessoas');
    // }

    /**
     * Get all of the contratos for the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }
}
