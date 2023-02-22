<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserStoredNotification extends Notification
{
    use Queueable;
    // Declaración de los atributos para la clase
    private string $user_name;
    private string $tipoUsuario_name;
    private string $temp_password;

    // Inicialización de los atributos por medio del constructor
    public function __construct(string $user_name, string $tipoUsuario_name, string $temp_password)
    {
        $this->user_name = $user_name;
        $this->tipoUsuario_name = $tipoUsuario_name;
        $this->temp_password = $temp_password;
    }

    // Se especifica el tipo de notificación
    public function via(mixed $notifiable)
    {
        return ['mail'];
    }

    // Se procede a definir el formato para el correo electrónico
    // https://laravel.com/docs/9.x/notifications#formatting-mail-messages
    public function toMail(mixed $notifiable)
    {
        $login_url = url('/login');
        return (new MailMessage)
            ->subject('Usuario registrado exitósamente.')
            ->line("Bienvenido $this->user_name")
            ->line("Has sido registrado en el Portal de la Comisión Emprende")
            ->line("Detalles del registro: \n")
            ->line("\t\t• Tipo de usuario asignado: $this->tipoUsuario_name")
            ->line("\t\t• Contraseña temporal para iniciar sesión: $this->temp_password")
            ->line("Puedes iniciar sesión en el sistema dando clic en el siguiente botón")
            ->action("Iniciar sesión", env('APP_FRONTEND_URL') . '/login')
            ->line("Para mayor seguridad, recuerda cambiar tu contraseña una vez verificado el acceso al sistema.");
    }


}
