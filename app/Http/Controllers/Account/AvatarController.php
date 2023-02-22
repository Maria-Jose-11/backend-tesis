<?php

namespace App\Http\Controllers\Account;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AvatarController extends Controller
{
    

//     /**
//     * @OA\Post(
//     * path= "/api/v1/profile/avatar ",
//     * operationId="Actualizar avatar",
//     * tags={"Perfil de usuario"},
//     * summary="Actualizar avatar",
//     * description="Actualizar avatar",
//     *security={
//     *  {"passport": {}},
//     *   },
//     * @OA\RequestBody(
//     *         @OA\JsonContent(),
//     *       @OA\MediaType(
//     *          mediaType="multipart/form-data",
//     *         @OA\Schema(
//     *            type="object",
//     *          required={"image"},
//     *         @OA\Property(property="image", type="file"),
//     * 
//        *         ),
//        *       ),
//        *     ),
       
//     * 
//     * 
//    *
//     *
//     *
//     *   @OA\Response(
//     *      response=200,
//     *       description="Success",
//     *      @OA\MediaType(
//     *           mediaType="application/json",
//     *      )
//     *   ),
//     *   @OA\Response(
//     *      response=401,
//     *       description="Unauthenticated"
//     *   ),
//     *   @OA\Response(
//     *      response=400,
//     *      description="Bad Request"
//     *   ),
//     *   @OA\Response(
//     *      response=404,
//     *      description="not found"
//     *   ),
//     *      @OA\Response(
//     *          response=403,
//     *          description="Forbidden"
//     *      )
//     * )
//     **/

    public function store(Request $request)
    {
        
        $request -> validate([
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:512'],
        ]);

        $user = $request->user();
        // $uploaded_image_path = ImageHelper::getLoadedImagePath(
        //     $request['image'],
        //     // https://styde.net/nuevo-operador-nullsafe-en-php-8/
        //     $user->image?->path,
        //     'avatars'
        // );

        $uploadedFileUrl = Cloudinary::upload($request->file('image')
            ->getRealPath(),['folder'=>'avatars'])
            ->getSecurePath();

        // Se hace uso del Trait para asociar esta imagen con el modelo user
        $user->attachImage($uploadedFileUrl);
        // Uso de la función padre
        return $this->sendResponse('Avatar updated successfully');

    }


}
