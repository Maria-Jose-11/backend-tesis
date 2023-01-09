<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends UserController
{
    public function __construct()
    {
        // Se porcede a establecer el gate
        // https://laravel.com/docs/9.x/authorization#via-middleware
        $this->middleware('can:manage-admin');
        // Se establece el rol para este usuario
        $tipoUsuario_slug = "admin";
        //$tipoUsuario_slug = "admin";
        // Se establece que si puede recibir notificaciones
        $can_receive_notifications = true;
        // Se hace uso del controlador padre
        parent::__construct($tipoUsuario_slug,$can_receive_notifications);
    }
}
