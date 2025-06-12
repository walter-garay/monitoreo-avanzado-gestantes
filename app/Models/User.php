<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DeviceToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'fecha_nacimiento',
        'fecha_inicio_gestacion',
        'peso_kg',
        'altura_cm',
        'rol',
        'email',
        'password',
        'centro_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fecha_nacimiento' => 'date',
            'fecha_inicio_gestacion' => 'date',
            'peso_kg' => 'float',
            'altura_cm' => 'float',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con CentroSalud
     */
    public function centro(): BelongsTo
    {
        return $this->belongsTo(CentroSalud::class, 'centro_id');
    }

    /**
     * Relación con DeviceToken
     */
    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }
    }
