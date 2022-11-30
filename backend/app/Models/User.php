<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, LaratrustUserTrait;

    protected $table = 'users';

    protected $perPage = 8;

    protected $casts = [
        'active' => 'bool',
        'owner' => 'int',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'poste_code',
        'active',
        'owner',
        'remember_token',

        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'owner');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
