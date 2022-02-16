<?php

namespace App\Models\Api_V2_0;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionsProducts extends Model
{
    use HasFactory;
    protected $table = 'ml_additions_products';

    protected $fillable = [
        'ml_additions_id', 'product_company_id', 'quantity', 'unitary_value', 'subtotal', 'cost',
        'monthly_result_value', 'monthly_cost_value', 'lease'
    ];
    public function ml_products_company()
    {
        return $this->belongsTo(ProductCompany::class, 'product_company_id');
    }

}
