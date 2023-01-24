<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Emprendimiento;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class EmprendimientoImagenController extends Controller
{
    public function store(Request $request, Emprendimiento $emprendimiento)
    {
        
        $request -> validate([
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:512'],
        ]);

        $emprendimiento_data = $request->all();
        $emprendimiento->fill($emprendimiento_data);
        

        $uploadedFileUrl = Cloudinary::upload($request->file('image')
            ->getRealPath(),['folder'=>'emprendimientos'])
            ->getSecurePath();

        // Se hace uso del Trait para asociar esta imagen con el modelo user
        $emprendimiento->attachImage($uploadedFileUrl);
        // Uso de la función padre
        return $this->sendResponse('Emprendimiento actualizado satisfactoriamente');

    }
}
