<?php

namespace App\Http\Controllers\Account;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    
    public function store(Request $request)
    {
        
        $request -> validate([
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:512'],
        ]);

        $user = $request->user();
        $uploaded_image_path = ImageHelper::getLoadedImagePath(
            $request['image'],
            // https://styde.net/nuevo-operador-nullsafe-en-php-8/
            $user->image?->path,
            'avatars'
        );

        // Se hace uso del Trait para asociar esta imagen con el modelo user
        $user->attachImage($uploaded_image_path);
        // Uso de la función padre
        return $this->sendResponse('Avatar updated successfully');

    }


}
