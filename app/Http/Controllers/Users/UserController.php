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
    protected string $tipoUsuario_nombre;

    // Atributo para enviar notificaciones
    protected bool $can_receive_notifications;

    // Creación del constructor
    public function __construct(string $tipoUsuario_nombre, bool $can_receive_notifications = false)
    {
        // https://laravel.com/docs/9.x/controllers#controller-middleware
        // Verifica si el usuario esta activo para hacer el update
        $this->middleware('is.user.active')->only('update');

        // Verifica dependiendo del rol para acceder a los metodos del controlador
        // https://laravel.com/docs/9.x/middleware#middleware-parameters
        $this->middleware("verify.user.role:$tipoUsuario_nombre")->only('show', 'update', 'destroy');

        // Inicializar el atributo de la clase
        $this->tipoUsuario_nombre = $tipoUsuario_nombre;

        // Inicializar el atributo de la clase
        $this->can_receive_notifications = $can_receive_notifications;
    }


    // Métodos del Controlador
    // Listar todos los usuarios
    public function index()
    {
        // Obtener el rol del usuario
        $tipoUsuario = Tipo_usuario::where('nombre', $this->tipoUsuario_nombre)->first();
        // Obtener los usuarios en base a la relación
        $users = $tipoUsuario->users;
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "Lista de usuarios generada exitosamente", result: [
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
            'personal_phone' => ['required', 'numeric', 'digits:10'],
            'linkedin' => ['required', 'string', 'min:3', 'max:35'],
            //'home_phone' => ['required', 'numeric', 'digits:9'],
            //'address' => ['required', 'string', 'min:5', 'max:50'],
        ]);

        // Obtiene el rol del usuario
        $tipoUsuario = Tipo_usuario::where('nombre', $this->tipoUsuario_nombre)->first();
        // Crear una instancia del usuario
        $user = new User($request->all());
        // Crear el password
        $temp_password = PasswordHelper::generatePassword();
        // Se setea el paasword al usuario
        $user->password = Hash::make($temp_password);
        // Se almacena el usuario en la BDD
        $tipoUsuario->users()->save($user);
        // Se establece si puede recibir notificaión
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
            'first_name' => ['required', 'string', 'min:3', 'max:35'],
            'last_name' => ['required', 'string',  'min:3', 'max:35'],
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'personal_phone' => ['required', 'numeric', 'digits:10'],
            'linkedin' => ['required', 'string', 'min:3', 'max:35'],
        ]);

        // Obtiene el email del usuario
        $old_user_email = $user->email;
        // Actaliza los datos del usuario
        $user->fill($user_data);
        // Guardar en la BDD
        $user->save();
        // Mandar la notificación si en el caso del que el correo sea diferente
        if ($this->can_receive_notifications && $old_user_email !== $user->email)
        {
            $temp_password = PasswordHelper::generatePassword();
            $user->password = Hash::make($temp_password);
            $user->save();
            $this->sendNotifications($user, $temp_password);
        }
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Usuario actualizado correctamente.');
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
