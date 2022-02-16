<?php

namespace App\Models\Api_V2_0;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTableVersion extends Model
{
    

    protected $table = 'ml_products_table_versions_prices';
    protected $fillable = [
        'products_id',
        'type',
        'version',
        'price',
        'price_fraction',
        'price_factory',
        'price_fraction_factory',
        'ipi'
    ];

    public function ml_products_company()
    {
        return $this->hasMany(ProductCompany::class, 'product_table_versions_prices_id');
    }

    public function ml_products()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
