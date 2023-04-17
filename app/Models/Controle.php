<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Controle extends Model
{
    use HasFactory;

    protected $table = 'controles';

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected $fillable = [
        'system_id', 'user_id', 'justification'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    /**
     * Converte o atributo created_at de string para data
     *
     * @return Carbon|null
     */
    public function getCreatedAtAttribute(): ?Carbon
    {
        return Carbon::make($this->attributes['created_at']);
    }

    /**
     * Converte o atributo created_at de string para data
     *
     * @return Carbon|null
     */
    public function getUpdatedAtAttribute(): ?Carbon
    {
        return Carbon::make($this->attributes['updated_at']);
    }
}
