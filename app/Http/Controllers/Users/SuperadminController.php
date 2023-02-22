<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperadminController extends UserController
{





    // Se crea el constructor para el controlador
    public function __construct()
    {
        // Se porcede a establecer el gate
        // https://laravel.com/docs/9.x/authorization#via-middleware
        $this->middleware('can:manage-superadmin');
        // Se establece el rol para este usuario
        $tipoUsuario_slug = "superadmin";
        //$tipoUsuario_slug = "admin";
        // Se establece que si puede recibir notificaciones
        $can_receive_notifications = true;
        parent::__construct($tipoUsuario_slug,$can_receive_notifications);
    }


}
