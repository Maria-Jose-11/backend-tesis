<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // https://laravel.com/docs/9.x/eloquent-resources#introduction
        return [
            'id' => $this->id,
            'Nombre' => $this->getFullName(),
            'Correo' => $this->email,
            'Rol' => $this->tipoUsuario->nombre,
            'Teléfono' => $this->personal_phone,
            'LinkedIn' => $this->linkedin,
            'Estado' => $this->state,
        ];


    }
}
