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
            'full_name' => $this->getFullName(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'rol' => $this->tipoUsuario->nombre,
            'Teléfono' => $this->personal_phone,
            'LinkedIn' => $this->linkedin,
            'state' => $this->state,
            'image' =>$this->getAvatarPath(),
        ];


    }
}
