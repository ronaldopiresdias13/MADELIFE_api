<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filter Empresa of user
 */
trait FiltroEmpresaProfissional
{
    public static function bootFiltroEmpresaProfissional()
    {
        if (auth()->check()) {
            // static::creating(function ($model) {
            //     $model->empresa_id = auth()->pessoa->prestador->empresa_id;
            // });
            static::addGlobalScope('empresa_id', function (Builder $builder) {
                if (auth()->check()) {
                    return $builder->where('empresa_id', auth()->user()->pessoa->profissional->empresa_id);
                }
            });
        }
    }
}
