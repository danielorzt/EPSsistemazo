<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Sigue siendo Authenticatable
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; //Añadir HasApiTokens para video
class Usuario extends Authenticatable // Nombre de clase cambiado
{
    use HasApiTokens, HasFactory, Notifiable;// Explicar para vídeo

    protected $table = 'usuarios'; // Especifica el nombre de la tabla si es diferente al plural del nombre del modelo en snake_case

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_usuario',
        'email',
        'contrasena',
        'rol_usuario',
        'paciente_id',
        'medico_id',
        'email_verificado_en',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contrasena',        // Coincide con 'contrasena'
        'remember_token',   // Coincide con el nombre de la columna 'remember_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verificado_en' => 'datetime', // Coincide con 'email_verificado_en'
            'contrasena' => 'hashed',           // Coincide con 'contrasena'
        ];
    }

    // Relaciones para vídeo
    // Relación: Un usuario puede ser un paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relación: Un usuario puede ser un médico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    // Renombrar el método para obtener la contraseña para la autenticación si es necesario
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}
