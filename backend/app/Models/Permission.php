<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',

        'created_at',
        'updated_at',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
