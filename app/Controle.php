<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controle extends Model
{
    protected $table = 'controles';

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected $fillable = [
        'sistema_id', 'user_id', 'justificativa'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sistema()
    {
        return $this->belongsTo(Sistema::class);
    }
}
