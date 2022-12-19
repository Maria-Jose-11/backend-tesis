<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Se procede a definir la estructura de la respuesta de la petición
        // https://laravel.com/docs/9.x/eloquent-resources#introduction
        return [
            //'username' => $this->username
            'id' => $this->id,
            'Nombre' => $this->first_name,
            'Apellido' => $this->last_name,
            'Correo' => $this->email,
            'Rol' => $this->tipoUsuario->nombre,
            'Telefono' => $this->personal_phone,
            'Linkedin' => $this->linkedin,
            'Estado' => $this->state,
            //'address' => $this->address,
        ];


    }
}
