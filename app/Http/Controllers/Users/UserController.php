<?php

namespace App\Http\Controllers\Users;

use App\Helpers\PasswordHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Tipo_usuario;
use App\Models\User;
use App\Notifications\UserStoredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Atributo para controlar los roles
    protected string $tipoUsuario_slug;

    // Atributo para enviar notificaciones
    protected bool $can_receive_notifications;

    // Creación del constructor
    public function __construct(string $tipoUsuario_slug, bool $can_receive_notifications = false)
    {
        // https://laravel.com/docs/9.x/controllers#controller-middleware
        // Verifica si el usuario esta activo para hacer el update
        $this->middleware('is.user.active')->only('update');

        // Verifica dependiendo del rol para acceder a los metodos del controlador
        // https://laravel.com/docs/9.x/middleware#middleware-parameters
        $this->middleware("verify.user.role:$tipoUsuario_slug")->only('show', 'update', 'destroy');

        $this->tipoUsuario_slug = $tipoUsuario_slug;

        // Inicializar el atributo de la clase
        $this->can_receive_notifications = $can_receive_notifications;
    }


    // Métodos del Controlador
    // Listar todos los usuarios

    /**
    * @OA\Get(
    *     path="/api/v1/superadmin",
    *     summary="Mostrar información de usuarios superadministradores",
    *     operationId="mostrarSuperadmins",
    *     tags={"Usuario Superadmin"},
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los emprendimientos."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )

    * @OA\Get(
    *     path="/api/v1/admin",
    *     summary="Mostrar información de usuarios administradores",
    *     operationId="mostrarAdmins",
    *     tags={"Usuario Admin"},
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
        // Obtener el rol del usuario
        $tipoUsuario = Tipo_usuario::where('slug', $this->tipoUsuario_slug)->first();
        $users = $tipoUsuario->users;
        return $this->sendResponse(message: 'Lista de usuarios generada correctamente', result: [
            'users' => UserResource::collection($users),
        ]);
    }

    // Crear un nuevo usuario

    /**
     * @OA\Post(
     * path= "/api/v1/superadmin/create",
     * operationId="crearSuperadmin",
     * tags={"Usuario Superadmin"},
     * summary="Crear información de superadministrador",
     * description="Crear información de superadministrador",

     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              type="object",
     *              required={"first_name", "last_name", "email"},
     *             
     *              @OA\Property(
     *                 property="first_name",
     *                type="string"
     *               
     *            ),
     *              @OA\Property(
     *                 property="last_name",
     *                type="string"
     *               
     *            ),
     *            @OA\Property(
        *                 property="email",

        *                type="email"
        *              
        *            ),

        *           @OA\Property(
        *                 property="personal_phone",
        *                type="integer"
        *               
        *            ),
        *           @OA\Property(
        *                 property="linkedin",
        *                type="string"
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
     *  @OA\Post(
        * path= "/api/v1/admin/create",
        * operationId="crearAdmin",
        * tags={"Usuario Admin"},
        * summary="Crear información de administrador",
        * description="Crear información de administrador",

        *      @OA\RequestBody(
        *          @OA\JsonContent(),
        *          @OA\MediaType(
        *              mediaType="multipart/form-data",
        *              @OA\Schema(
        *              type="object",
        *              required={"first_name", "last_name", "email"},
        *             
        *              @OA\Property(
        *                 property="first_name",
        *                type="string"
        *               
        *            ),
        *              @OA\Property(
        *                 property="last_name",
        *                type="string"
        *               
        *            ),
        *            @OA\Property(
            *                 property="email",

            *                type="email"
            *              
            *            ),

            *           @OA\Property(
            *                 property="personal_phone",
            *                type="integer"
            *               
            *            ),
            *           @OA\Property(
            *                 property="linkedin",
            *                type="string"
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
         // Validación de los datos de entrada
        $request -> validate([
            'first_name' => ['required', 'string', 'min:3', 'max:35'],
            'last_name' => ['required', 'string', 'min:3', 'max:35'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'personal_phone' => ['numeric', 'digits:10'],
            'linkedin' => ['string', 'min:3', 'max:255'],
        ]);
        $tipoUsuario = Tipo_usuario::where('slug', $this->tipoUsuario_slug)->first();
        $user = new User($request->all());
        $temp_password = PasswordHelper::generatePassword();
        $user->password = Hash::make($temp_password);
        $tipoUsuario->users()->save($user);
        if ($this->can_receive_notifications)
        {
            // Se procede a invocar la función para en envío de una notificación
            $this->sendNotifications($user, $temp_password);
        }
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Usuario registrado correctamente');
    }



    // Mostrar la información personal del usuario

    /**
    * @OA\Get(
    *     path="/api/v1/superadmin/{superadmin}",
    *     summary="Mostrar información de un superadministrador en específico",
    *     operationId="mostrarSuperadmin",
    *     tags={"Usuario Superadmin"},
    *      @OA\Parameter(
    *          name="superadmin",
    *          description="id de superadministrador",
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

    * @OA\Get(
    *     path="/api/v1/admin/{admin}",
    *     summary="Mostrar información de un administrador en específico",
    *     operationId="mostrarAdmin",
    *     tags={"Usuario Admin"},
    *      @OA\Parameter(
    *          name="admin",
    *          description="id de administrador",
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

    public function show(User $user)
    {
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Perfil de usuario', result: [
            'user' => new ProfileResource($user),
            'imagen' => $user->getAvatarPath(),
            //'imagen_path'=> $user->image->path,
        ]);
    }


    /**
     * @OA\Post(
     * path= "/api/v1/superadmin/{superadmin}/update",
     * operationId="actualizarSuperadmin",
     * tags={"Usuario Superadmin"},
     * summary="Actualizar información de superadministrador",
     * description="Actualizar información de superadministrador",
     * @OA\Parameter(
     *          name="superadmin",
     *          description="id de superadministrador",
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
     *                 property="first_name",
     *                type="string"
     *               
     *            ),
     *              @OA\Property(
     *                 property="last_name",
     *                type="string"
     *               
     *            ),
     *            @OA\Property(
        *                 property="email",

        *                type="email"
        *              
        *            ),

        *           @OA\Property(
        *                 property="personal_phone",
        *                type="integer"
        *               
        *            ),
        *           @OA\Property(
        *                 property="linkedin",
        *                type="string"
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
     *  @OA\Post(
        * path= "/api/v1/admin/{admin}/update",
        * operationId="actualizarAdmin",
        * tags={"Usuario Admin"},
        * summary="Actualizar información de administrador",
        * description="Actualizar información de administrador",
        *@OA\Parameter(
     *          name="admin",
     *          description="id de administrador",
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
        *                 property="first_name",
        *                type="string"
        *               
        *            ),
        *              @OA\Property(
        *                 property="last_name",
        *                type="string"
        *               
        *            ),
        *            @OA\Property(
            *                 property="email",

            *                type="email"
            *              
            *            ),

            *           @OA\Property(
            *                 property="personal_phone",
            *                type="integer"
            *               
            *            ),
            *           @OA\Property(
            *                 property="linkedin",
            *                type="string"
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


    // Actualizar el usuario
    public function update(Request $request, User $user)
    {
         // Validación de los datos de entrada
        $user_data=$request -> validate([
            'first_name' => ['string', 'min:3', 'max:35'],
            'last_name' => ['string',  'min:3', 'max:35'],
            'email' => ['string', 'email', 'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'personal_phone' => ['numeric', 'digits:10'],
            'linkedin' => ['string', 'min:3', 'max:255'], 
        ]);

        $old_user_email = $user->email;
        $user->fill($user_data);
        $user->save();
        if ($this->can_receive_notifications && $old_user_email !== $user->email)
        {
            $temp_password = PasswordHelper::generatePassword();
            $user->password = Hash::make($temp_password);
            $user->save();
            $this->sendNotifications($user, $temp_password);
        }
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Usuario actualizado correctamente');
    }


    // Dar de baja a un usuario


    /**
     * @OA\Get(
     * path= "/api/v1/superadmin/{superadmin}/destroy",
     * operationId="desactivarSuperadmin",
     * tags={"Usuario Superadmin"},
     * summary="Desactivar información de superadministrador",
     * description="Desactivar información de superadministrador",
     * @OA\Parameter(
     *          name="superadmin",
     *          description="id de superadministrador",
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
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     * 
     *  @OA\Get(
        * path= "/api/v1/admin/{admin}/destroy",
        * operationId="desactivarAdmin",
        * tags={"Usuario Admin"},
        * summary="Desactivar información de administrador",
        * description="Desactivar información de administrador",
        *@OA\Parameter(
     *          name="admin",
     *          description="id de administrador",
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

    public function destroy(User $user)
    {
        // Obtiene el estado del usuario
        $user_state = $user->state;
        // Crear un mensaje en base al estado del usuario
        $message = $user_state ? 'desactivado' : 'activado';
        // Cambiar el estado
        $user->state = !$user_state;
        // Guardar en la BDD
        $user->save();
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "Usuario $message correctamente");
    }



    // Función para enviar notificaciones para el usuario registrado
    private function sendNotifications(User $user, string $temp_password)
    {
        // https://laravel.com/docs/9.x/notifications#sending-notifications
        $user->notify(
            new UserStoredNotification(
                user_name: $user->getFullName(),
                tipoUsuario_name: $user->tipoUsuario->nombre,
                temp_password: $temp_password
            )
        );
    }


}
