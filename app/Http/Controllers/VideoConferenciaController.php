<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoConferenciaResource;
use App\Models\VideoConferencia;
use Illuminate\Http\Request;

class VideoConferenciaController extends Controller
{
    public function index()
    {
        $videoconferencias = VideoConferencia::all();
        
        return $this->sendResponse(message: 'Lista de videoconferencias', 
            result: [
                'video_conferencias' => VideoConferenciaResource::collection($videoconferencias),
        ]);
    }

    
    public function store(Request $request)
    {
        $request -> validate([
            'nombre' => ['required', 'string', 'min:4', 'max:100'] ,
            'url' => ['required', 'string'],
            
        ]);
        $videoconferencias_data = $request->all();
        $videoconferencias = new VideoConferencia($videoconferencias_data);
        $videoconferencias->save();

        return $this->sendResponse(message: 'Datos de videoconferencia almacenados exitosamente');
    }

    public function show(VideoConferencia $videoconferencia)
    {
        return $this->sendResponse(message: 'Detalle de la videoconferencia', result: [
            'video_conferencias' => new VideoConferenciaResource($videoconferencia),
        ]);
    }

    public function update(Request $request, VideoConferencia $videoconferencia)
    {
        $request -> validate([
            'nombre' => ['required', 'string', 'min:4', 'max:100'] ,
            'url' => ['required', 'string'],
        ]);
        $videoconferencia_data = $request->all();
        $videoconferencia->fill($videoconferencia_data)->save();

        return $this->sendResponse(message: 'Actualización exitosa');
    }

}
