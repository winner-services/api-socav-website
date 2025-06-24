<?php

namespace App\Http\Controllers\Temoignage;

use App\Http\Controllers\Controller;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TemoignageController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getAllTemoignage",
     *      operationId="getTemoignage",
     *      tags={"Temoignage"},
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
    public function getTemoignage()
    {
        $data = Temoignage::inRandomOrder()->get();

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
     * path="/api/createTemoignage",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="storeTemoignage",
     * tags={"Temoignage"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"name","fonction_en","fonction_fr","description_en","description_fr","image"},
     *       @OA\Property(property="name", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="fonction_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="fonction_fr", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="description_en", type="string", format="text",example="2024-09-19"),  
     *       @OA\Property(property="description_fr", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="image", type="string", format="text", example="winner")
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
    public function storeTemoignage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'fonction_en' => 'nullable',
            'fonction_fr' => 'nullable',
            'description_en' => 'required',
            'description_fr' => 'required',
            'image' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images/Temoignage', 'public');
        }

        Temoignage::create([
            'name' => $request->name,
            'fonction_en' => $request->fonction_en,
            'fonction_fr' => $request->fonction_fr,
            'description_en' => $request->description_en,
            'description_fr' => $request->description_fr,
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
     * path="/api/updateTemoignage/{id}",
     * summary="Update",
     * description="Modification",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateTemoignage",
     * tags={"Temoignage"},
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
     *       required={"name","fonction_en","fonction_fr","description_en","description_fr","image"},
     *       @OA\Property(property="name", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="fonction_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="fonction_fr", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="description_en", type="string", format="text",example="2024-09-19"),  
     *       @OA\Property(property="description_fr", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="image", type="string", format="text", example="winner")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Temoignage not found",
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error",
     * )
     * )
     */
    public function updateTemoignage(Request $request, $id)
    {
        $temoignage = Temoignage::find($id);

        if (!$temoignage) {
            return response()->json(['message' => 'Temoignage not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'fonction_en' => 'nullable',
            'fonction_fr' => 'nullable',
            'description_en' => 'required',
            'description_fr' => 'required',
            'image' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($temoignage->image);

            $file = $request->file('image');
            $path = $file->store('images/Temoignage', 'public');
        } else {
            $path = $temoignage->image;
        }

        $temoignage->update([
            'name' => $request->name,
            'fonction_en' => $request->fonction_en,
            'fonction_fr' => $request->fonction_fr,
            'description_en' => $request->description_en,
            'description_fr' => $request->description_fr,
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
     * path="/api/deleteTemoignage/{id}",
     * summary="Delete",
     * description="Suppression",
     * security={{ "bearerAuth":{ }}},
     * operationId="deleteTemoignage",
     * tags={"Temoignage"},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Temoignage deleted successfully",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Temoignage not found",
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     * )
     * )
     */
    public function deleteTemoignage($id)
    {
        $temoignage = Temoignage::find($id);

        if (!$temoignage) {
            return response()->json(['message' => 'Temoignage not found'], 404);
        }
        $temoignage->delete();

        return response()->json([
            'message' => "Temoignage deleted successfully",
            'success' => true,
            'status' => 200
        ]);
    }
}
