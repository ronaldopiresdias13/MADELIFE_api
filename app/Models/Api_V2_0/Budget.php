<?php

namespace App\Models\Api_V2_0;

use App\Models\Cidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'ml_budgets';

    public function package()
    {
        return $this->belongsTo(Package::class, 'packages_id');
    }
    /**
     * Get the cidade that owns the Orc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'city_id');
    }
}
