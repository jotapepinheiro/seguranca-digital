<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait CreatedUpdatedBy
{
    protected static function boot()
    {
        parent::boot();

        if(Auth::user()) {
            static::creating(function($model)
            {
                $userId = Auth::id();
                $model->created_by = $userId;
            });
            static::updating(function($model)
            {
                $userId = Auth::id();
                $model->updated_by = $userId;
            });
        }

    }

}
