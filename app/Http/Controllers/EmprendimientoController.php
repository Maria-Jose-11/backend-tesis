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

    /**
    * @OA\Get(
    *     path="/api/v1/emprendimiento",
    *     summary="Mostrar información de emprendimientos",
    *     operationId="mostrarEmprendimientos",
    *     tags={"Emprendimientos"},
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los emprendimientos."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
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

    /**
     * @OA\Post(
     * path= "/api/v1/emprendimiento/create",
     * operationId="crearEmprendimiento",
     * tags={"Emprendimientos"},
     * summary="Crear información de emprendimiento",
     * description=" Crear la información de emprendimiento",

     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              type="object",
     *              required={"rol_esfot", "nombre", "descripcion"},
     *             
     *              @OA\Property(
     *                 property="rol_esfot",
     *                type="string"
     *               
     *            ),
     *              @OA\Property(
     *                 property="nombre",
     *                type="string"
     *               
     *            ),
     *            @OA\Property(
        *                 property="descripcion",

        *                type="string"
        *              
        *            ),
        *            @OA\Property(
        *                 property="categoria",
        *                type="string"
        *              
        *            ),
        *            @OA\Property(
        *                 property="direccion",
        *                type="string"
        *               
        *            ),
        *           @OA\Property(
        *                 property="cobertura",
        *                type="string"
        *               
        *            ),
        *           @OA\Property(
        *                 property="pagina_web",
        *                type="string"
        *               
        *            ),
        *           @OA\Property(
        *                 property="telefono",
        *                type="integer"
        *               
        *            ),
        *           @OA\Property(
        *                 property="whatsapp",
        *                type="string"
        *               
        *            ),

            *           @OA\Property(
        *                 property="facebook",
        *                type="string"
        *               
        *            ),
        *               @OA\Property(
        *                 property="instagram",
        *                type="string"
        *               
        *            ),
        *            @OA\Property(
        *                 property="descuento",
        *                type="integer"
        *              
        *            ),
        *            @OA\Property(
        *                 property="image",
        *                type="file"
        *              
        *            ),

     *            ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     *
     */
    public function store(Request $request)
    {
        $request -> validate([
            'rol_esfot' => ['required', 'string', 'min:1', 'max:50'] ,
            'nombre' => ['required', 'string', 'min:4', 'max:255'],
            'descripcion'=> ['required', 'string', 'min:4', 'max:255'],
            'categoria' => ['required', 'string', 'min:4', 'max:255'],
            'direccion' => ['string', 'min:4', 'max:255'],
            'cobertura' => ['string', 'min:4', 'max:255'],
            'pagina_web' => ['string', 'min:4', 'max:255'],
            'telefono'=> ['numeric', 'digits:10'],
            'whatsapp' => ['string', 'min:4', 'max:50'],
            'facebook' => ['string', 'min:4', 'max:255'],
            'instagram' => ['string', 'min:4', 'max:255'],
            'descuento'=>['numeric', 'digits_between:1,2'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:512'],
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

         /**
    * @OA\Get(
    *     path="/api/v1/emprendimiento/{emprendimiento}",
    *     summary="Mostrar información de un emprendimiento en específico",
    *     operationId="mostrarEmprendimiento",
    *     tags={"Emprendimientos"},
    *      @OA\Parameter(
    *          name="emprendimiento",
    *          description="id de emprendimiento",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer",
    *              format="int64"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent()
    *      ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
     *
     */
    public function show(Emprendimiento $emprendimiento)
    {
        return $this->sendResponse(message: 'Detalle de emprendimiento', result: [
            'emprendimiento' => new EmprendimientoResource($emprendimiento),
        ]);
    }

    /**
     * @OA\Post(
     * path= "/api/v1/emprendimiento/{emprendimiento}/update",
     * operationId="updateEmprendimiento",
     * tags={"Emprendimientos"},
     * summary="Actualizar la información de un emprendimiento",
     * description=" Actualizar la información de una cárcel",
     *      @OA\Parameter(
     *          name="emprendimiento",
     *          description="id del emprendimiento",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              type="object",
     *             
     *              @OA\Property(
     *                 property="rol_esfot",
     *                type="string"
     *               
     *            ),
     *              @OA\Property(
     *                 property="nombre",
     *                type="string"
     *               
     *            ),
     *            @OA\Property(
        *                 property="descripcion",

        *                type="string"
        *              
        *            ),
        *            @OA\Property(
        *                 property="categoria",
        *                type="string"
        *              
        *            ),
        *            @OA\Property(
        *                 property="direccion",
        *                type="string"
        *               
        *            ),
        *           @OA\Property(
        *                 property="cobertura",
        *                type="string"
        *               
        *            ),
        *           @OA\Property(
        *                 property="pagina_web",
        *                type="string"
        *               
        *            ),
        *           @OA\Property(
        *                 property="telefono",
        *                type="integer"
        *               
        *            ),
        *           @OA\Property(
        *                 property="whatsapp",
        *                type="string"
        *               
        *            ),

            *           @OA\Property(
        *                 property="facebook",
        *                type="string"
        *               
        *            ),
        *               @OA\Property(
        *                 property="instagram",
        *                type="string"
        *               
        *            ),
        *            @OA\Property(
        *                 property="descuento",
        *                type="integer"
        *              
        *            ),
        *            @OA\Property(
        *                 property="image",
        *                type="file"
        *              
        *            ),

     *            ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     *
     */
    public function update(Request $request, Emprendimiento $emprendimiento)
    {
        $request -> validate([
            'rol_esfot' => ['string', 'min:1', 'max:50'] ,
            'nombre' => ['string', 'min:4', 'max:255'],
            'descripcion'=> ['string', 'min:4', 'max:255'],
            'categoria' => ['string', 'min:4', 'max:255'],
            'direccion' => ['string', 'min:4', 'max:255'],
            'cobertura' => ['string', 'min:4', 'max:255'],
            'pagina_web' => ['string', 'min:4', 'max:255'],
            'telefono'=> ['numeric', 'digits:10'],
            'whatsapp' => ['string', 'min:4', 'max:50'],
            'facebook' => ['string', 'min:4', 'max:255'],
            'instagram' => ['string', 'min:4', 'max:255'],
            'descuento'=>['numeric', 'digits_between:1,2'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:512'],
        ]);
        $emprendimiento_data = $request->all();
        $emprendimiento->fill($emprendimiento_data)->save();

        if ($request->has('image'))
        {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')
            ->getRealPath(),['folder'=>'emprendimientos'])->getSecurePath();
            $emprendimiento->attachImage($uploadedFileUrl);
        }
        return $this->sendResponse(message: 'Datos de emprendimiento actualizados exitosamente', result: [
            'emprendimiento' => new EmprendimientoResource($emprendimiento),
        ]);
    }

     /**
    * @OA\Get(
    *     path="/api/v1/emprendimiento/{emprendimiento}/destroy",
    *     summary="Eliminar emprendimientos",
    *     operationId="destroyEmprendimiento",
    *     tags={"Emprendimientos"},
    *      @OA\Parameter(
    *          name="emprendimiento",
    *          description="id de emprendimiento",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer",
    *              format="int64"
    *          )
    *      ),

    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent()
    *      ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
     *
     */


    public function destroy(Emprendimiento $emprendimiento)
    {
        $emprendimiento_state = $emprendimiento->state;
        $message = $emprendimiento_state ? 'desactivado' : 'activado';
        $emprendimiento->state = !$emprendimiento_state;
        $emprendimiento->save();
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
