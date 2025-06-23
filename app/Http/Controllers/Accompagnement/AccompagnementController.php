<?php

namespace App\Http\Controllers\Accompagnement;

use App\Http\Controllers\Controller;
use App\Models\Accompagnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccompagnementController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getAccompaniementData",
     *      operationId="getAccompaniementData",
     *      tags={"Accompaniement"},
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
    public function getAccompaniementData()
    {
        $data = Accompagnement::latest()->get();
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
     * path="/api/createAccompaniement",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="createAccompaniement",
     * tags={"Accompaniement"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"description_fr","description_en"},
     *       @OA\Property(property="description_fr", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="description_en", type="string", format="text",example="rdc")
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
    public function createAccompaniement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description_fr' => 'required',
            'description_en' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        Accompagnement::create([
            'description_fr' => $request->description_fr,
            'description_en' => $request->description_en,

        ]);

        $result = [
            'message' => "success",
            'success' => true,
            'status' => 201
        ];
        return response()->json($result);
    }
    /**
     * @OA\Put(
     * path="/api/updateAccompaniement/{id}",
     * summary="Update",
     * description="Modification",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateAccompaniement",
     * tags={"Accompaniement"},
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
     *       required={"description_fr","description_en"},
     *       @OA\Property(property="description_fr", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="description_en", type="string", format="text",example="rdc")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Accompaniement not found",
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error",
     * )
     * )
     */
    public function updateAccompaniement(Request $request, $id)
    {
        $accompagnement = Accompagnement::find($id);

        if (!$accompagnement) {
            return response()->json(['message' => 'accompagnement not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'description_fr' => 'required',
            'description_en' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }

        $accompagnement->update([
            'description_fr' => $request->description_fr,
            'description_en' => $request->description_en,
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
     * path="/api/deleteAccompaniement/{id}",
     * summary="Delete",
     * description="Suppression",
     * security={{ "bearerAuth":{ }}},
     * operationId="deleteAccompaniement",
     * tags={"Accompaniement"},
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
    public function deleteAccompaniement($id)
    {
        $accompagnement = Accompagnement::find($id);

        $accompagnement->delete();

        return response()->json([
            'message' => "deleted successfully",
            'success' => true,
            'status' => 200
        ]);
    }
}
