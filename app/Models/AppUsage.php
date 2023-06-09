<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppUsage extends Model
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
    
    public $fillable = [
        'order_id',
        'device_type',
        'app_version'
    ];
}
