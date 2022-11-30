<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    protected $table = 'produits';

    protected $perPage = 10;

    protected $casts = [
        'gamme_id' => 'int',
        'fournisseur_id' => 'int',
    ];

    protected $fillable = [
        'name',
        'code',
        'slug',
        'gamme_id',
        'fournisseur_id',

        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(fn ($model) => $model['slug'] = StringHelper::toSlug($model['name']));
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function gamme(): BelongsTo
    {
        return $this->belongsTo(Gamme::class);
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }
}
