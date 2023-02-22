<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenEmprendimiento extends Model
{
    use HasFactory;

    // public function emprendimientos()
    // {
    //     return $this->hasMany(Emprendimiento::class);
    // }

    protected $fillable = [
        'path',
    ];

    public function emprendimientos()
    {
        return $this->hasMany(Emprendimiento::class);
    }
}
