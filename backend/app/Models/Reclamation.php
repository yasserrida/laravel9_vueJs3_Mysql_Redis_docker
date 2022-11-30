<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Reclamation extends Model
{
    use SoftDeletes;

    protected $table = 'reclamations';

    protected $perPage = 8;

    protected $casts = [
        'solution' => 'bool',
        'user_id' => 'int',
        'contrat_id' => 'int',
    ];

    protected $fillable = [
        'canal',
        'qualification',
        'reclamant',
        'status',
        'date_mail',
        'date_courier',
        'solution',
        'date_cloture',

        'user_id',
        'contrat_id',

        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model['user_id'] = Auth::guard('api')->id();
        });
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
