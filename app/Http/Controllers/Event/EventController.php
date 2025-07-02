<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getEventData",
     *      operationId="getEventData",
     *      tags={"Event"},
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
    public function getEventData()
    {
        $data = Events::latest()->get();
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
     * path="/api/createEvent",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="createEvent",
     * tags={"Event"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"title_en","title_fr","date","description_en","description_fr"},
     *       @OA\Property(property="title_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="title_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="date", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="description_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="description_fr", type="string", format="text",example="rdc"),
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
    public function createEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_fr' => 'required',
            'date' => 'required',
            'description_en' => 'required',
            'description_fr' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        $file = $request->file('image');
        $path = $file->store('images/Event', 'public');
        Events::create([
            'title_en' => $request->title_en,
            'title_fr' => $request->title_fr,
            'date' => $request->date,
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
     * path="/api/updateEvent/{id}",
     * summary="Update",
     * description="Modification",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateEvent",
     * tags={"Event"},
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
     *       required={"title_en","title_fr","date","description_en","description_fr"},
     *       @OA\Property(property="title_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="title_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="date", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="description_en", type="string", format="text",example="rdc"),
     *       @OA\Property(property="description_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="image", type="string", format="text",example="rdc")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     * ),
     * @OA\Response(
     *    response=404,
     *    description="news not found",
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error",
     * )
     * )
     */
    public function updateEvent(Request $request, $id)
    {
        $news = Events::find($id);

        if (!$news) {
            return response()->json(['message' => 'room not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_fr' => 'required',
            'date' => 'required',
            'description_en' => 'required',
            'description_fr' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($news->image);

            $file = $request->file('image');
            $path = $file->store('images/Event', 'public');
        } else {
            $path = $news->image;
        }

        $news->update([
            'title_en' => $request->title_en,
            'title_fr' => $request->title_fr,
            'date' => $request->date,
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
     * @OA\Get(
     *     path="/api/getEventsById/{id}",
     *     summary="Afficher",
     *     description="Afficher ",
     *     security={{"bearerAuth":{}}},
     *     operationId="getEventsById",
     *     tags={"Event"},
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
    public function getEventsById($id)
    {
        $data = Events::find($id);
        $result = [
            'message' => "success",
            'success' => true,
            'status' => 200,
            'data' => $data
        ];
        return response()->json($result);
    }

    public function deleteEvent($id)
    {
        $event = Events::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        Storage::disk('public')->delete($event->image);

        $event->delete();

        return response()->json([
            'message' => "deleted successfully",
            'success' => true,
            'status' => 200
        ]);

    }
}
