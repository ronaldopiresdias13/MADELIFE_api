<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdemservicoAcesso extends Model
{
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
        'check'
    ];

    // protected $guarded = [];

    // public function orcamento()
    // {
    //     return $this->belongsTo(Orcamento::class);
    // }
}
