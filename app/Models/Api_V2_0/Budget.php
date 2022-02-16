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

    protected $fillable = [
        'company_id',
        'packages_id',
        'city_id',
        'addition_code',
        'description',
        'objective',
        'accepted',
        'status',
        'budget_number',
        'budget_type',
        'process_number',
        'date',
        'situation',
        'quantity',
        'version',
        'unity'
    ];

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
