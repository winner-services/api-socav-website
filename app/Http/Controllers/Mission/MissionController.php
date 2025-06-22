<?php

namespace App\Http\Controllers\Mission;

use App\Http\Controllers\Controller;
use App\Models\Missions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\New_;

class MissionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getMissionsData",
     *      operationId="getMissionsData",
     *      tags={"Mission"},
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
    public function getMissionsData()
    {
        $data = Missions::latest()->get();
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
     * path="/api/createMission",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="createMission",
     * tags={"Mission"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"title_fr","title_en","description_fr","description_en"},
     *       @OA\Property(property="title_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="title_en", type="string", format="text",example="rdc"),
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
    public function createMission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_fr' => 'required',
            'title_en' => 'required',
            'description_fr' => 'required',
            'description_en' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        Missions::create([
            'title_fr' => $request->title_fr,
            'title_en' => $request->title_en,
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
     * path="/api/updateMission/{id}",
     * summary="Update",
     * description="Modification",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateMission",
     * tags={"Mission"},
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
     *       required={"title_fr","title_en","description_fr","description_en"},
     *       @OA\Property(property="title_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="title_en", type="string", format="text",example="rdc"),
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
     *    description="Mission not found",
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error",
     * )
     * )
     */
    public function updateMission(Request $request, $id)
    {
        $mission = Missions::find($id);

        if (!$mission) {
            return response()->json(['message' => 'mission not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title_fr' => 'required',
            'title_en' => 'required',
            'description_fr' => 'required',
            'description_en' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mission->update([
            'title_fr' => $request->title_fr,
            'title_en' => $request->title_en,
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
     * path="/api/deleteMission/{id}",
     * summary="Delete",
     * description="Suppression",
     * security={{ "bearerAuth":{ }}},
     * operationId="deleteMission",
     * tags={"Mission"},
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
    public function deleteMission($id)
    {
        $mission = Missions::find($id);

        $mission->delete();

        return response()->json([
            'message' => "Faqs deleted successfully",
            'success' => true,
            'status' => 200
        ]);
    }
}
