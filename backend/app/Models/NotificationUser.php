<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotificationUser extends Model
{
    protected $table = 'notifications_user';

    protected $casts = [
        'status' => 'bool',
        'temps_traitement' => 'int',
        'archiver' => 'bool',
        'contrat_id' => 'int',
        'user_id' => 'int',
    ];

    protected $fillable = [
        'name',
        'status',
        'temps_traitement',
        'archiver',
        'user_id',

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
