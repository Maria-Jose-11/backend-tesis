<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Whoops\Run;

class ProfileController extends Controller
{
        
     /**
    *  *@OA\Get(
    * path= "/api/v1/profile",
    * operationId="Mostrar perfil",
    * tags={"Perfil de usuario"},
    * summary="Mostrar perfil del usuario autenticado",
    * description="Mostrar perfil del usuario",
        *security={
        *  {"passport": {}},
        *   },
        *
        *
        *   @OA\Response(
        *      response=200,
        *       description="Success",
        *      @OA\MediaType(
        *           mediaType="application/json",
        *      )
        *   ),
        *   @OA\Response(
        *      response=401,
        *       description="Unauthenticated"
        *   ),
        *   @OA\Response(
        *      response=400,
        *      description="Bad Request"
        *   ),
        *   @OA\Response(
        *      response=404,
        *      description="not found"
        *   ),
        *      @OA\Response(
        *          response=403,
        *          description="Forbidden"
        *      )
        * )
        **/

    public function show()
    {
        $user = Auth::user();
        return $this->sendResponse(message: "Perfil de usuario", result: [
            'user' => new ProfileResource($user),
            'avatar' => $user->getAvatarPath()
        ]);
    }

     /**
     * @OA\Post(
     * path= "/api/v1/profile",
     * operationId="Actualizar Perfil",
     * tags={"Perfil de usuario"},
     * summary="Actualiza la información del usuario autenticado",
     * description="Actualizar información",
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              type="object",
     *              @OA\Property(property="first_name", type="string"),
     *               @OA\Property(property="last_name", type="string"),
     *            ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      ),
     *       @OA\Response(
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
        // Validar que el usuario sea mayor de edad
        $allowed_date_range =[
            'max' => date('Y-m-d', strtotime('-70 years')),
            'min' => date('Y-m-d', strtotime('-18 years')),
        ];

        // Validación de los datos de entrada
        $request -> validate([
            'first_name' => ['required', 'string', 'min:3', 'max:35'],
            'last_name' => ['required', 'string', 'min:3', 'max:35'],
            'personal_phone' => ['numeric', 'digits:10'],
            'linkedin' => ['string', 'min:3', 'max:255'],
        ]);

        // Se obtiene el modelo del usuario
        $user = $request->user();
        // Se actualiza el modelo en la BDD
        // https://laravel.com/docs/9.x/queries#update-statements
        $user->update($request->all());
        // Se invoca a la función padre
        return $this->sendResponse('Profile updated successfully');
    }


}