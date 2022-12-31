<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmprendimientoResource;
use App\Models\Emprendimiento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmprendimientoController extends Controller
{
    private string $directory_name = 'emprendimientos';

    //Visualizar la lista  de emprendimientos
    public function index()
    {
        $user = Auth::user();
        $emprendimientos = Emprendimiento::all();
        return $this->sendResponse(message: 'Lista de emprendimientos creada exitosamente', 
            result: [
                'emprendimientos' => EmprendimientoResource::collection($emprendimientos)
        ]);
    }

    // Crear un nuevo emprendimiento
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request -> validate([
            'rol_esfot' => ['required', 'string', 'min:10', 'max:14'] ,
            'nombre' => ['required', 'string', 'min:4', 'max:50'],
            'descripcion'=> ['required', 'string', 'min:4', 'max:50'],
            'categoria' => ['string', 'min:5', 'max:20'],
            'direccion' => ['string', 'min:5', 'max:100'],
            'cobertura' => ['string', 'min:5', 'max:30'],
            'pagina_web' => ['min:4', 'max:50'],
            'telefono'=> ['numeric', 'digits:10'],
            'whatsapp' => ['string', 'min:4', 'max:50'],
            'facebook' => ['string', 'min:4', 'max:50'],
            'instagram' => ['string', 'min:4', 'max:50'],
            'porcentaje'=>['numeric', 'digits:2'],
        ]);

        $emprendimiento_data = $request->all();
        $emprendimiento = new Emprendimiento($emprendimiento_data);
        $user = Auth::user();
        // Del usuario se almacena su reporte en base a la relación
        // https://laravel.com/docs/9.x/eloquent-relationships#the-save-method
        $user->emprendimientos()->save($emprendimiento);


        // Si del request se tiene una imagen se invoca al helper
        // if ($request->has('image'))
        // {
        //     $image_path = ImageHelper::getLoadedImagePath(
        //         uploaded_image: $request['image'],
        //         directory: $this->directory_name
        //     );
        //     // se hace uso del Trait para asociar esta imagen con el modelo report
        //     $report->attachImage($image_path);
        // }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Datos de emprendimiento almacenados exitosamente');
    }

    // Mostrar la información del reporte
    public function show(Emprendimiento $emprendimiento)
    {

        // Invoca el controlador padre para la respuesta json
        // El moldeo de la información por el Resource
        return $this->sendResponse(message: 'Detalle de emprendimiento', result: [
            'emprendimiento' => new EmprendimientoResource($emprendimiento),
        ]);
    }

    // Actualizar la información del reporte
    public function update(Request $request, Emprendimiento $emprendimiento)
    {
        // Validación de los datos de entrada
        $request -> validate([
            'rol_esfot' => ['required', 'string', 'min:10', 'max:14'] ,
            'nombre' => ['required', 'string', 'min:4', 'max:50'],
            'descripcion'=> ['required', 'string', 'min:4', 'max:50'],
            'categoria' => ['string', 'min:5', 'max:20'],
            'direccion' => ['string', 'min:5', 'max:100'],
            'cobertura' => ['string', 'min:5', 'max:30'],
            'pagina_web' => ['min:4', 'max:50'],
            'telefono'=> ['numeric', 'digits:10'],
            'whatsapp' => ['string', 'min:4', 'max:50'],
            'facebook' => ['string', 'min:4', 'max:50'],
            'instagram' => ['string', 'min:4', 'max:50'],
            'porcentaje'=>['numeric', 'digits:2'],
        ]);
        $emprendimiento_data = $request->all();
        $emprendimiento->fill($emprendimiento_data)->save();

        // Si del request se tiene una imagen se invoca al helper
        // if ($request->has('image'))
        // {
        //     $image_path = ImageHelper::getLoadedImagePath(
        //         uploaded_image: $request['image'],
        //         previous_image_path: $report->image?->path,
        //         directory: $this->directory_name
        //     );
        //     // se hace uso del Trait para asociar esta imagen con el modelo report
        //     $report->attachImage($image_path);
        // }
        // Invoca el controlador padre para la respuesta json
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
}
