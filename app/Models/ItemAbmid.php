<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemAbmid extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'itens_abmids';

    protected $fillable = [
        'abmid_id', 'descricao','item','ponto'
    ];

    protected $guarded = [];

    public function abmid(){
        return $this->belongsTo(PlanilhaAbmid::class,'abmid_id','id');
    }
}
