<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Contrat extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $recordEvents = ['created', 'updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => $eventName == 'created' ? 'Création' : 'Mise à jour')
            ->useLogName('contrat')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at']);
    }

    protected $table = 'contrats';

    protected $casts = [
        'produit_id' => 'int',
        'personne_id' => 'int',
        'user_id' => 'int',
    ];

    protected $fillable = [
        'numero_contrat',
        'date_signature',
        'date_reception',
        'date_envoi',
        'date_effet',
        'source',
        'rib',
        'iban',
        'iban_titulaire',
        'statut',
        'sous_statut',
        'produit_id',
        'user_id',

        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (Auth::guard('api')->check()) {
                $model['user_id'] = Auth::guard('api')->id();
            } else {
                $model['user_id'] = 1;
            }
        });
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }
}
