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
            //'username' => $this->username,
            'full_name' => $this->getFullName(),
            'email' => $this->email,
            'tipo_usuario_id' => $this->tipoUsuario->nombre,
            //'home_phone' => $this->home_phone,
            //'personal_phone' => $this->personal_phone,
        ];


    }
}
