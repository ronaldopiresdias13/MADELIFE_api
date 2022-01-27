<?php

namespace App\Models\Api_V2_0;

use App\Models\Tipoproduto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'ml_products';
    protected $fillable = [
        'empresas_id',
        'table_type',
        'code',
        'description',
        'code_apresentation',
        'content',
        'unidademedidas_id',
        'ean',
        'tiss',
        'tuss',
        'product_type_id',
        'is_hospital',
        'is_generic'
    ];

    public function ml_products_table_versions_prices()
    {
        return $this->hasOne(ProductTableVersion::class, 'products_id');
    }

    public function tipoproduto()
    {
        return $this->belongsTo(Tipoproduto::class, 'product_type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}
