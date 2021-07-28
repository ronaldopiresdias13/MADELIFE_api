<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Folga extends Model
{
    use Uuid;
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Get the escala that owns the Folga
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function escala(): BelongsTo
    {
        return $this->belongsTo(Escala::class);
    }
    public function prestador(): BelongsTo
    {
        return $this->belongsTo(Prestador::class);
    }
    public function substituto(): BelongsTo
    {
        return $this->belongsTo(Prestador::class, 'substituto', 'id');
    }
}
