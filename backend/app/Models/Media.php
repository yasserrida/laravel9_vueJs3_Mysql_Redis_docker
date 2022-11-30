<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media';

    protected $casts = [
        'contrat_id' => 'int',
        'user_id' => 'int',
        'ticket_id' => 'int',
    ];

    protected $fillable = [
        'file_name',
        'collection_name',
        'collection_subname',
        'name',
        'mime_type',
        'contrat_id',
        'user_id',
        'ticket_id',

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

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
