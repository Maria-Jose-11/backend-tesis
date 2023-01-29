<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Http\Resources\EmprendimientoResource;
use App\Models\Emprendimiento;
use App\Models\Image;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmprendimientoController extends Controller
{

    //Visualizar la lista  de emprendimientos
    public function index()
    {
        //$user = Auth::user();
        $emprendimientos = Emprendimiento::all();
        
        return $this->sendResponse(message: 'Lista de emprendimientos', 
            result: [
                'emprendimientos' => EmprendimientoResource::collection($emprendimientos),
                //'imagen' =>$emprendimientos->getImagePath(),
        ]);
    }

    // Crear un nuevo emprendimiento
    public function store(Request $request)
    {
        $request -> validate([
            'rol_esfot' => ['required', 'string', 'min:4', 'max:14'] ,
            'nombre' => ['required', 'string', 'min:4', 'max:50'],
            'descripcion'=> ['required', 'string', 'min:4', 'max:100'],
            'categoria' => ['string', 'min:5', 'max:20'],
            'direccion' => ['string', 'min:5', 'max:100'],
            'cobertura' => ['string', 'min:5', 'max:30'],
            'pagina_web' => ['min:4', 'max:50'],
            'telefono'=> ['numeric', 'digits:10'],
            'whatsapp' => ['string', 'min:4', 'max:50'],
            'facebook' => ['string', 'min:4', 'max:50'],
            'instagram' => ['string', 'min:4', 'max:50'],
            'descuento'=>['numeric', 'digits:2'],
            //'image' => ['image', 'mimes:jpg,png,jpeg', 'max:512'],
        ]);
        $emprendimiento_data = $request->all();
        $emprendimiento = new Emprendimiento($emprendimiento_data);
        $emprendimiento->save();

        if ($request->has('image'))
        {
             $uploadedFileUrl = Cloudinary::upload($request->file('image')
             ->getRealPath(),['folder'=>'emprendimientos'])
             ->getSecurePath();

             $emprendimiento->attachImage($uploadedFileUrl);
        }

        return $this->sendResponse(message: 'Datos de emprendimiento almacenados exitosamente');
    }

    // Mostrar la información del reporte
    public function show(Emprendimiento $emprendimiento)
    {
        return $this->sendResponse(message: 'Detalle de emprendimiento', result: [
            'emprendimiento' => new EmprendimientoResource($emprendimiento),
            //'imagen' => $emprendimiento->getImagePath(),
            //'imagen1' => $emprendimiento->image->path,
        ]);
    }

    public function update(Request $request, Emprendimiento $emprendimiento)
    {
        $request -> validate([
            'rol_esfot' => ['string', 'min:10', 'max:14'] ,
            'nombre' => ['string', 'min:4', 'max:50'],
            'descripcion'=> ['string', 'min:4', 'max:100'],
            'categoria' => ['string', 'min:5', 'max:20'],
            'direccion' => ['string', 'min:5', 'max:100'],
            'cobertura' => ['string', 'min:5', 'max:30'],
            'pagina_web' => ['min:4', 'max:50'],
            'telefono'=> ['numeric', 'digits:10'],
            'whatsapp' => ['string', 'min:4', 'max:50'],
            'facebook' => ['string', 'min:4', 'max:50'],
            'instagram' => ['string', 'min:4', 'max:50'],
            'descuento'=>['numeric', 'digits:2'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:512'],
        ]);
        $emprendimiento_data = $request->all();
        $emprendimiento->fill($emprendimiento_data)->save();

        // if ($request->has('image'))
        // {
        //     $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(),['folder'=>'emprendimientos'])->getSecurePath();
        //     $emprendimiento->attachImage($uploadedFileUrl);
        // }
        return $this->sendResponse(message: 'Datos de emprendimiento actualizados exitosamente');
    }

    // Dar de baja a un pabellon
    public function destroy(Emprendimiento $emprendimiento)
    {
        // Obtener el estado del reporte
        $emprendimiento_state = $emprendimiento->state;
        // Almacenar un string con el mensaje
        $message = $emprendimiento_state ? 'desactivado' : 'activado';
        // Cambia el estado del pabellon
        $emprendimiento->state = !$emprendimiento_state;
        // Guardar en la BDD
        $emprendimiento->save();
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "Emprendimiento $message correctamente");
    }

    public function estado(Emprendimiento $emprendimiento)
    {
        // Obtener el estado del reporte
        $segundo_estado = $emprendimiento->segundo_estado;
        // Almacenar un string con el mensaje
        $message = $segundo_estado ? 'pendiente' : 'aprobado';
        // Cambia el estado del pabellon
        $emprendimiento->segundo_estado = !$segundo_estado;
        // Guardar en la BDD
        $emprendimiento->save();
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "Emprendimiento $message");
    }
}
