<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $casts = [
        'user_id' => 'int',
    ];

    protected $fillable = [
        'uuid',
        'title',
        'message',
        'priority',
        'statut',
        'label',
        'categorie',
        'is_resolved',
        'user_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model['user_id'] = Auth::guard('api')->id();
            $model['uuid'] = Str::uuid();
        });
    }

}
