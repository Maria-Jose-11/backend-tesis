<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmprendimientoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rol_esfot' => $this->rol_esfot,
            'nombre' => $this->nombre,
            'descripcion'  => $this->descripcion,
            'categoria'  => $this->categoria,
            'direccion' => $this->direccion,
            'cobertura'  => $this->cobertura,
            'pagina_web' => $this->pagina_web,
            'telefono' => $this->telefono,
            'whatsapp' => $this->whatsapp,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'porcentaje' => $this->porcentaje,
            // 'created_by' => new UserResource($this->user),
            // 'created_at' => $this->created_at->toDateTimeString(),
            //'admin' => UserResource::collection($this->users),
        ];
    }
}
