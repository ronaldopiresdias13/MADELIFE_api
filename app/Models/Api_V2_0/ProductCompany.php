<?php

namespace App\Models\Api_V2_0;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCompany extends Model
{
    protected $guarded = [];

    protected $table = 'ml_products_company';

    public function ml_products_table_versions_prices()
    {
        return $this->belongsTo(ProductTableVersion::class, 'product_table_versions_prices_id');
    }
}
