<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NeadKatz extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'neads_katz';

    protected $fillable = [
        'neads_id', 'categoria','value','pontuacao'
    ];

    protected $guarded = [];

    public function nead(){
        return $this->belongsTo(Nead::class,'neads_id','id');
    }
}
