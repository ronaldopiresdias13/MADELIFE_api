<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orc extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'empresa_id',
        'orc_id',
        'cliente_id',
        'pacote_id',
        'numero',
        'addition_code',
        'tipo',
        'tipoatentendimento',
        'indicacaoacidente',
        'data',
        'quantidade',
        'unidade',
        'cidade_id',
        'processo',
        'caraterAtendimento',
        'situacao',
        'descricao',
        'versao',
        'indicacaoClinica',
        'valortotalproduto',
        'valortotalcusto',
        'valortotalservico',
        'valordesconto',
        'valortotalorcamento',
        'observacao',
        'status',
        'venda_realizada',
        'venda_data',
        'homecare_paciente_id',
        'aph_descricao',
        'aph_endereco',
        'aph_cep',
        'aph_cidade_id',
        'evento_nome',
        'evento_endereco',
        'evento_cep',
        'evento_cidade_id',
        'remocao_nome',
        'remocao_sexo',
        'remocao_nascimento',
        'remocao_cpfcnpj',
        'remocao_rgie',
        'remocao_enderecoorigem',
        'remocao_cidadeorigem_id',
        'remocao_enderecodestino',
        'remocao_cidadedestino_id',
        'remocao_observacao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the paciente that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'homecare_paciente_id');
    }

    /**
     * Get the cliente that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the cidade that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    /**
     * Get all of the orcProdutos for the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produtos(): HasMany
    {
        return $this->hasMany(OrcProduto::class);
    }

    /**
     * Get all of the orcServicos for the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicos(): HasMany
    {
        return $this->hasMany(OrcServico::class);
    }

    /**
     * Get all of the custos for the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function custos(): HasMany
    {
        return $this->hasMany(Orccusto::class);
    }

    /**
     * Get the homecare_paciente that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function homecare_paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'homecare_paciente_id');
    }

    /**
     * Get the aph_cidade that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aph_cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'aph_cidade_id');
    }

    /**
     * Get the evento_cidade that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evento_cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'evento_cidade_id');
    }

    /**
     * Get the remocao_cidadeorigem that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function remocao_cidadeorigem(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'remocao_cidadeorigem_id');
    }

    /**
     * Get the remocao_cidadedestino that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function remocao_cidadedestino(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'remocao_cidadedestino_id');
    }
}
