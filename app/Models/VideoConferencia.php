<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoConferencia extends Model
{
    use HasFactory;

    protected $fillable =[
        'nombre',
        'url',
        'descripcion'

    ];
    //relación con usuarios
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
