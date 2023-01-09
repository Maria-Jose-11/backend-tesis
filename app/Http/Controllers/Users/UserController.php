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
    public function index()
    {
        // Obtener el rol del usuario
        $tipoUsuario = Tipo_usuario::where('slug', $this->tipoUsuario_slug)->first();
        // Obtener los usuarios en base a la relación
        $users = $tipoUsuario->users;
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Lista de usuarios generada correctamente', result: [
            'users' => UserResource::collection($users),
        ]);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
         // Validación de los datos de entrada
        $request -> validate([
            'first_name' => ['required', 'string', 'min:3', 'max:35'],
            'last_name' => ['required', 'string', 'min:3', 'max:35'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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
    public function show(User $user)
    {
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Perfil de usuario', result: [
            'user' => new ProfileResource($user),
        ]);
    }



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
