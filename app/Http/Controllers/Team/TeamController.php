<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getAllTeamData",
     *      operationId="getAllTeamData",
     *      tags={"Team"},
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
    public function getAllTeamData()
    {
        $data = Team::latest()->get();
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
     * path="/api/createTeam",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="storeTeam",
     * tags={"Team"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"name","title_en","title_fr","facebook","email","twitter","linkedin","image"},
     *       @OA\Property(property="name", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="title_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="title_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="facebook", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="email", type="string", format="text",example="rdc"),
     *       @OA\Property(property="twitter", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="linkedin", type="string", format="text",example="rdc"),
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
    public function storeTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title_en' => 'required',
            'title_fr' => 'required',
            'facebook' => 'nullable',
            'email' => 'nullable|email',
            'twitter' => 'nullable',
            'linkedin' => 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        $file = $request->file('image');
        $path = $file->store('images/Team', 'public');
        Team::create([
            'name' => $request->name,
            'title_en' => $request->title_en,
            'title_fr' => $request->title_fr,
            'facebook' => $request->facebook,
            'email' => $request->email,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
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
     * path="/api/updateTeam/{id}",
     * summary="Update",
     * description="Modification",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateTeam",
     * tags={"Team"},
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
     *       required={"name","title_en","title_fr"},
     *       @OA\Property(property="name", type="string", format="text", example="winner kambale"),
     *       @OA\Property(property="title_en", type="string", format="text", example="rdc"),
     *       @OA\Property(property="title_fr", type="string", format="text", example="rdc"),
     *       @OA\Property(property="facebook", type="string", format="text", example="winner kambale"),
     *       @OA\Property(property="email", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="twitter", type="string", format="text", example="winner kambale"),
     *       @OA\Property(property="linkedin", type="string", format="text", example="rdc"),
     *       @OA\Property(property="image", type="string", format="text", example="rdc"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Team member not found",
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error",
     * )
     * )
     */
    public function updateTeam(Request $request, $id)
    {
        $teamMember = Team::find($id);

        if (!$teamMember) {
            return response()->json(['message' => 'Team member not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title_en' => 'required',
            'title_fr' => 'required',
            'facebook' => 'nullable',
            'email' => 'nullable|email',
            'twitter' => 'nullable',
            'linkedin' => 'nullable',
            'image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {

            Storage::disk('public')->delete($teamMember->image);

            $file = $request->file('image');
            $path = $file->store('images/Team', 'public');
        } else {
            $path = $teamMember->image;
        }

        $teamMember->update([
            'name' => $request->name,
            'title_en' => $request->title_en,
            'title_fr' => $request->title_fr,
            'description_en' => $request->description_en,
            'description_fr' => $request->description_fr,
            'email' => $request->email,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
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
     * path="/api/deleteTeam/{id}",
     * summary="Delete",
     * description="Suppression",
     * security={{ "bearerAuth":{ }}},
     * operationId="deleteTeam",
     * tags={"Team"},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Team member deleted successfully",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Team member not found",
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     * )
     * )
     */
    public function deleteTeam($id)
    {
        $teamMember = Team::find($id);

        if (!$teamMember) {
            return response()->json(['message' => 'Team member not found'], 404);
        }
        Storage::disk('public')->delete($teamMember->image);

        $teamMember->delete();

        return response()->json([
            'message' => "Team member deleted successfully",
            'success' => true,
            'status' => 200
        ]);
    }
}
