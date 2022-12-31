<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprendimiento extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'rol_esfot',
        'nombre',
        'descripcion',
        'categoria',
        'direccion',
        'cobertura',
        'pagina_web',
        'telefono',
        'whatsapp',
        'facebook',
        'instagram',
        'porcentaje'
    
    ];



    // Relación de uno a muchos
    // Un reporte le pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica uno a uno
    // Un reporte pueden tener una imagen
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
}
