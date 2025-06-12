<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sintoma extends Model
{
    use HasFactory;

    protected $fillable = [
        'hora_inicio',
        'hora_fin',
        'severidad',
        'descripcion',
        'gestante_id',
    ];

    protected $casts = [
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
    ];

    /**
     * Relación con la gestante (User)
     */
    public function gestante()
    {
        return $this->belongsTo(User::class, 'gestante_id');
    }
}

