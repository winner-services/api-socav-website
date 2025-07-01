<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class ServiceController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getServiceData",
     *      operationId="getServiceData",
     *      tags={"Service"},
     *      summary="Get",
     *      description="Returns list",
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     * *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *       ),
     *     )
     */
    public function getServiceData()
    {
        $data = Services::latest()->get();
        $result = [
            'message' => "success",
            'success' => true,
            'status' => 200,
            'data' => $data
        ];
        return response()->json($result);
    }
    /**
     * @OA\Post(
     * path="/api/createService",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="createService",
     * tags={"Service"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"title_en","title_fr","description_fr","description_en"},
     *       @OA\Property(property="title_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="title_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="description_fr", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="description_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="image", type="string", format="text",example="rdc")
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="success",
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="ysteme",
     *     )
     * )
     */
    public function createService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_fr' => 'required',
            'description_fr' => 'required',
            'description_en' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        $file = $request->file('image');
        $path = $file->store('images/Service', 'public');
        Services::create([
            'title_en' => $request->title_en,
            'title_fr' => $request->title_fr,
            'description_fr' => $request->description_fr,
            'description_en' => $request->description_en,
            'image' => $path
        ]);
        $result = [
            'message' => "success",
            'success' => true,
            'status' => 201
        ];
        return response()->json($result);
    }
    /**
     * @OA\Post(
     * path="/api/updateService/{id}",
     * summary="Update",
     * description="Modification",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateService",
     * tags={"Service"},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     *    required=false,
     *    description="Modifier",
     *    @OA\JsonContent(
     *       required={"title_en","title_fr","description_fr","description_en"},
     *       @OA\Property(property="description_fr", type="string", format="text", example="winner services"),
     *       @OA\Property(property="description_en", type="string", format="text", example="winner services"),
     *       @OA\Property(property="title_en", type="string", format="text", example="winner kambale"),
     *       @OA\Property(property="title_fr", type="string", format="text", example="rdc"),
     *       @OA\Property(property="image", type="string", format="text", example="rdc"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Service not found",
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error",
     * )
     * )
     */
    public function updateService(Request $request, $id)
    {
        $service = Services::find($id);

        if (!$service) {
            return response()->json(['message' => 'service not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_fr' => 'required',
            'description_fr' => 'required',
            'description_en' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($service->image);

            $file = $request->file('image');
            $path = $file->store('images/service', 'public');
        } else {
            $path = $service->image;
        }

        $service->update([
            'title_en' => $request->title_en,
            'title_fr' => $request->title_fr,
            'description_fr' => $request->description_fr,
            'description_en' => $request->description_en,
            'image' => $path
        ]);

        $result = [
            'message' => "success",
            'success' => true,
            'status' => 200
        ];
        return response()->json($result);
    }
    /**
     * @OA\Delete(
     * path="/api/deleteService/{id}",
     * summary="Delete",
     * description="Suppression",
     * security={{ "bearerAuth":{ }}},
     * operationId="deleteService",
     * tags={"Service"},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Slide deleted successfully",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Slide not found",
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     * )
     * )
     */
    public function deleteService($id)
    {
        $service = Services::find($id);

        if (!$service) {
            return response()->json(['message' => 'service not found'], 404);
        }
        Storage::disk('public')->delete($service->image);

        $service->delete();

        return response()->json([
            'message' => "Slide deleted successfully",
            'success' => true,
            'status' => 200
        ]);
    }
    /**
     * @OA\Get(
     *     path="/api/getSingleService/{id}",
     *     summary="Afficher",
     *     description="Afficher ",
     *     security={{"bearerAuth":{}}},
     *     operationId="getSingleService",
     *     tags={"Service"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'approv",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="succès")
     *         ),
     * @OA\Response(
     *    response=401,
     *    description="le systeme",
     *     )
     *     ),
     *     )
     * )
     */
    public function getSingleService($id)
    {
        $data = Services::find($id);
        $result = [
            'message' => "success",
            'success' => true,
            'status' => 200,
            'data' => $data
        ];
        return response()->json($result);
    }
}
