<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NeadGrupo2 extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'neads_grupo_2';

    protected $fillable = [
        'neads_id', 'categoria','value'
    ];

    protected $guarded = [];

    public function nead(){
        return $this->belongsTo(Nead::class,'neads_id','id');
    }
}
