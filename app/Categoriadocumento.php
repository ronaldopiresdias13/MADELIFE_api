<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoriadocumento extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'empresa_id',
        'categoria',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // protected $guarded = [];

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'categoria_id');
    }
}
