<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicao extends Model
{
    protected $table = 'medicoes';
    protected $guarded = [];

    /**
     * Get the cliente that owns the Medicao
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the ordemservico that owns the Medicao
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordemservico(): BelongsTo
    {
        return $this->belongsTo(Ordemservico::class);
    }

    public function medicao_servicos()
    {
        return $this->hasMany(ServicoMedicao::class, 'medicoes_id')->where('ativo', true);
    }

    public function medicao_produtos()
    {
        return $this->hasMany(ProdutoMedicao::class, 'medicoes_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentariomedicao::class, 'medicoes_id');
    }

    /**
     * Get the profissional that owns the Medicao
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profissional(): BelongsTo
    {
        return $this->belongsTo(Profissional::class);
    }
}
