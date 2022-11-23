<?php

namespace App\Traits;

use Carbon\Carbon;
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

    /**
     * Converte o atributo created_at de string para data
     *
     * @return Carbon|null
     */
    public function getCreatedByAttribute(): ?Carbon
    {
        return Carbon::make($this->attributes['created_at']);
    }

    /**
     * Converte o atributo created_at de string para data
     *
     * @return Carbon|null
     */
    public function getUpdatedByAttribute(): ?Carbon
    {
        return Carbon::make($this->attributes['updated_at']);
    }

}
