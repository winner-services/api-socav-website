<?php

namespace App\Http\Controllers\Faqs;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getFaqsData",
     *      operationId="getFaqsData",
     *      tags={"Faqs"},
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
    public function getFaqsData()
    {
        $data = Faqs::latest()->get();
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
     * path="/api/createFaqs",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="createFaqs",
     * tags={"Faqs"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"question_en","question_fr","response_fr","response_en"},
     *       @OA\Property(property="question_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="question_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="response_fr", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="response_en", type="string", format="text",example="rdc")
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
    public function createFaqs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_en' => 'required',
            'question_fr' => 'required',
            'response_fr' => 'required',
            'response_en' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        Faqs::create([
            'question_en' => $request->question_en,
            'question_fr' => $request->question_fr,
            'response_fr' => $request->response_fr,
            'response_en' => $request->response_en
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
     * path="/api/updateFaqs/{id}",
     * summary="Update",
     * description="Modification",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateFaqs",
     * tags={"Faqs"},
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
     *       required={"question_en","question_fr","response_fr","response_en"},
     *       @OA\Property(property="question_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="question_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="response_fr", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="response_en", type="string", format="text",example="rdc")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="faqs not found",
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error",
     * )
     * )
     */
    public function updateFaqs(Request $request, $id)
    {
        $faqs = Faqs::find($id);

        if (!$faqs) {
            return response()->json(['message' => 'faqs not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'question_en' => 'required',
            'question_fr' => 'required',
            'response_fr' => 'required',
            'response_en' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        $faqs->update([
            'question_en' => $request->question_en,
            'question_fr' => $request->question_fr,
            'response_fr' => $request->response_fr,
            'response_en' => $request->response_en
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
     * path="/api/deleteFaqs/{id}",
     * summary="Delete",
     * description="Suppression",
     * security={{ "bearerAuth":{ }}},
     * operationId="deleteFaqs",
     * tags={"Faqs"},
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
    public function deleteFaqs($id)
    {
        $faqs = Faqs::find($id);

        $faqs->delete();

        return response()->json([
            'message' => "Faqs deleted successfully",
            'success' => true,
            'status' => 200
        ]);
    }
}
