<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gamme extends Model
{
    protected $table = 'gammes';

    protected $perPage = 10;

    protected $fillable = [
        'name',
        'slug',

        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(fn ($model) => $model['slug'] = StringHelper::toSlug($model['name']));
    }

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }
}
