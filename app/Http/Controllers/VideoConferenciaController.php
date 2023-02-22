<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoConferenciaResource;
use App\Models\VideoConferencia;
use Illuminate\Http\Request;

class VideoConferenciaController extends Controller
{

    /**
    * @OA\Get(
    *     path="/api/v1/videoconferencia",
    *     summary="Mostrar información de videoconferencias",
    *     operationId="mostrarVideoconferencias",
    *     tags={"Videoconferencias"},
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar información."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function index()
    {
        $videoconferencias = VideoConferencia::all();
        
        return $this->sendResponse(message: 'Lista de videoconferencias', 
            result: [
                'video_conferencias' => VideoConferenciaResource::collection($videoconferencias),
        ]);
    }

     /**
     * @OA\Post(
     * path= "/api/v1/videoconferencia/create",
     * operationId="crearVideoconferencia",
     * tags={"Videoconferencias"},
     * summary="Crear información de videoconferencias",
     * description="Crear la información de videoconferencias",

     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              type="object",
     *              required={"nombre", "url"},
     *             
     *              @OA\Property(
     *                 property="nombre",
     *                type="string"
     *               
     *            ),
     * 
     *              @OA\Property(
     *                 property="url",
     *                type="string"
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
            'nombre' => ['required', 'string', 'min:4', 'max:255'] ,
            'url' => ['required', 'string'],
            'descripcion' => ['required', 'string']
            
        ]);
        $videoconferencias_data = $request->all();
        $videoconferencias = new VideoConferencia($videoconferencias_data);
        $videoconferencias->save();

        return $this->sendResponse(message: 'Datos de videoconferencia almacenados exitosamente', result: [
            'video_conferencias' => new VideoConferenciaResource($videoconferencias),
        ]);
    }


         /**
    * @OA\Get(
    *     path="/api/v1/videoconferencia/{videoconferencia}",
    *     summary="Mostrar información específica de una videoconferencia",
    *     operationId="mostrarVideoconferencia",
    *     tags={"Videoconferencias"},
    *      @OA\Parameter(
    *          name="videoconferencia",
    *          description="id de videoconferencia",
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
    public function show(VideoConferencia $videoconferencia)
    {
        return $this->sendResponse(message: 'Detalle de la videoconferencia', result: [
            'video_conferencias' => new VideoConferenciaResource($videoconferencia),
        ]);
    }


     /**
     * @OA\Post(
     * path= "/api/v1/videoconferencia/{videoconferencia}/update",
     * operationId="actualizarVideoconferencia",
     * tags={"Videoconferencias"},
     * summary="Actualizar información de videoconferencias",
     * description="Actualizar la información de videoconferencias",
     *      @OA\Parameter(
     *          name="videoconferencia",
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
     *              required={"nombre", "url"},
     *             
     *              @OA\Property(
     *                 property="nombre",
     *                type="string"
     *               
     *            ),
     * 
     *              @OA\Property(
     *                 property="url",
     *                type="string"
     *            ),
     * 
     *              @OA\Property(
     *                 property="descripcion",
     *                type="string"
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
    public function update(Request $request, VideoConferencia $videoconferencia)
    {
        $request -> validate([
            'nombre' => ['required', 'string', 'min:4', 'max:100'] ,
            'url' => ['required', 'string'],
            'descripcion' => ['required', 'string']
        ]);
        $videoconferencia_data = $request->all();
        $videoconferencia->fill($videoconferencia_data)->save();

        return $this->sendResponse(message: 'Actualización exitosa', result: [
            'video_conferencias' => new VideoConferenciaResource($videoconferencia),
        ]);
    }

}
