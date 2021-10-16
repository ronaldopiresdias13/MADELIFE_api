<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use Uuid;
    use HasFactory;
    
    protected $guarded = [];

    public function anexo(){
        return $this->morphTo();
    }
}
