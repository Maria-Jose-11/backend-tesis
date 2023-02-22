<?php

namespace App\Models;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprendimiento extends Model
{
    use HasFactory, HasImage;
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
        'descuento',
        'state',
        'segundo_estado',
        'imagen'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function imagen()
    {
        return $this->belongsTo(ImagenEmprendimiento::class);
    }

    



    // Relación polimórfica uno a uno
    // Un reporte pueden tener una imagen
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }


    public function getDefaultAvatarPath()
    {
        return "https://cdn-icons-png.flaticon.com/512/711/711769.png";
    }


    public function getImagePath()
    {
        if (!$this->image)
        {
            return $this->getDefaultAvatarPath();
        }
        return $this->image->path;
    }
}
