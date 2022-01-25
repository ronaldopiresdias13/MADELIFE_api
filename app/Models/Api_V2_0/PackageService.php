<?php

namespace App\Models\Api_V2_0;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageService extends Model
{
    protected $table = 'ml_packages_services';
    protected $fillable = ['servico_id', 'packages_id'];

    public function ml_package()
    {
        return $this->belongsTo(Package::class, 'packages_id');
    }

   public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
