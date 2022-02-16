<?php

namespace App\Models\Api_V2_0;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'ml_packages';
    protected $fillable = ['description', 'empresas_id', 'code'];
  
    public function ml_packages_product()
    {
        return $this->hasMany(PackageProduct::class, 'packages_id');
    }

    public function ml_packages_services()
    {
        return $this->hasMany(PackageService::class, 'packages_id');
    }
}
