<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'tipo_usuario_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'linkedin',
        'personal_phone',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function tipoUsuario()
    {
        return $this->belongsTo(Tipo_usuario::class);
    }

    // Obtener el nombre completo del usuario
    public function getFullName()
    {
        return "$this->first_name $this->last_name";
    }
    // Relación polimórfica uno a uno
    // Un usuario pueden tener una imagen
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }

    // Crear un avatar por default
    public function getDefaultAvatarPath()
    {
        return "https://cdn-icons-png.flaticon.com/512/711/711769.png";
    }

    // Obtener la imagen de la BDD
    public function getAvatarPath()
    {
        // se verifica no si existe una iamgen
        if (!$this->image)
        {
            // asignarle el path de una imagen por defecto
            return $this->getDefaultAvatarPath();
        }
        // retornar el path de la imagen registrada en la BDD
        return $this->image->path;
    }
    
    public function emprendimientos()
    {
        return $this->hasMany(Emprendimiento::class);
    }

        // Función para saber si el rol que tiene asignado el usuario
    // es el mismo que se le esta pasando a la función
    // https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
    public function hasRole(string $tipoUsuario_slug)
    {
        return $this->tipoUsuario->slug === $tipoUsuario_slug;
    }


    public function videoconferencias()
    {
        return $this->hasMany(VideoConferencia::class);
    }


    
    

}
