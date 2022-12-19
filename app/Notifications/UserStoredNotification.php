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
        return (new MailMessage)
            ->subject('Rwgistro completado')
            ->line("Bienvenido $this->user_name")
            ->line("Te encuentras registrado en el sistema ...(nombre del sistema)")
            ->line("Detalles de registro:")
            ->line("Rol de usuario: $this->tipoUsuario_name")
            ->line("Contraseña temporal para acceder al sistema: $this->temp_password")
            ->line("You can login our system by clicking on the following button")
            ->action("Login", env('APP_FRONTEND_URL') . '/login')
            ->line("Remember: you have to change the temporary password once you login.");
    }


}
