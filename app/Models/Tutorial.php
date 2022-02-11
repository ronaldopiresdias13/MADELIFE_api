<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutorial extends Model
{
    use HasFactory;
    use softDeletes;

    protected $guarded = [];

    public function tutorial_files(): HasMany
    {
        return $this->hasMany(TutorialFile::class);
    }
}
