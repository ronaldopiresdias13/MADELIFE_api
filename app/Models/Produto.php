<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Produto extends Model
{
    protected $guarded = [];
    // protected static function booted()
    // {
    //     $empresa_id = null;

    //     if (Auth::check()) {
    //         $user = Auth::user();
    //         if ($user->pessoa) {
    //             if ($user->pessoa->profissional) {
    //                 $empresa_id = Auth::user()->pessoa->profissional->empresa_id;
    //             }
    //         }
    //     }

    //     static::addGlobalScope('empresa', function (Builder $builder) use ($empresa_id) {
    //         $builder->where('empresa_id', $empresa_id);
    //     });
    // }

    public function transcricao_produto()
    {
        return $this->hasOne(TranscricaoProduto::class)->where('ativo', true);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Get the unidademedida that owns the Produto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unidademedida(): BelongsTo
    {
        return $this->belongsTo(Unidademedida::class);
    }
}
