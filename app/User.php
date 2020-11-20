<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use Uuid;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cpfcnpj', 'email', 'password', 'pessoa_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function acessos()
    {
        return $this->belongsToMany(Acesso::class, 'user_acesso')->withPivot('id')->wherePivot('ativo', true);
    }
}
