<?php

namespace App\Http\Resources;
use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class EmprendimientoResource extends JsonResource
{
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
            'descuento' => $this->descuento,
            'estado' => $this->state,
            'estado1' => $this->segundo_estado,
            'image' =>$this->getImagePath(),
            //'image' => ImageHelper::getDiskImageUrl($this->getImagePath()),
        ];
    }
}
