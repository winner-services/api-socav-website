<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Contact;
use App\Models\Events;
use App\Models\Project;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getAllAboutData",
     *      operationId="getAllAboutData",
     *      tags={"About"},
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
    public function getAllAboutData()
    {
        $data = About::latest()->first();
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
     * path="/api/createAbout",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="createAbout",
     * tags={"About"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"objective_fr","objective_en","title_en","title_fr","short_description_en","short_description_fr","address","facebook","instagram","linkedin","twitter","email","phone","youtube","image1","image2"},
     *       @OA\Property(property="objective_fr", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="objective_en", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="title_en", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="title_fr", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="short_description_en", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="short_description_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="address", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="facebook", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="instagram", type="string", format="text",example="winner"),
     *       @OA\Property(property="linkedin", type="string", format="text",example="winner"),
     *       @OA\Property(property="twitter", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="email", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="phone", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="youtube", type="string", format="text",example="winner"),
     *       @OA\Property(property="image1", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="image2", type="string", format="text",example="mpesa")
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="success",
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="error",
     *     )
     * )
     */
    public function createAbout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_fr' => 'required',
            'short_description_en' => 'required',
            'short_description_fr' => 'required',
            'address' => 'required',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'linkedin' => 'nullable',
            'twitter' => 'nullable',
            'youtube' => 'nullable',
            'email' => 'required',
            'phone' => 'required',
            'objective_fr' => 'required',
            'objective_en' => 'required',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }
        $file1 = $request->file('image1');
        $path1 = $file1->store('images/About', 'public');
        $file2 = $request->file('image2');
        $path2 = $file2->store('images/About', 'public');

        About::create([
            'title_en' => $request->title_en,
            'title_fr' => $request->title_fr,
            'short_description_en' => $request->short_description_en,
            'short_description_fr' => $request->short_description_fr,
            'address' => $request->address,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'youtube' => $request->youtube,
            'email' => $request->email,
            'phone' => $request->phone,
            'objective_fr' => $request->objective_fr,
            'objective_en' => $request->objective_en,
            'image1' => $path1,
            'image2' => $path2
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
     * path="/api/updateAbout/{id}",
     * summary="Update",
     * description="Update existing About entry",
     * security={{ "bearerAuth":{ }}},
     * operationId="updateAbout",
     * tags={"About"},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="ID of the About entry to update",
     *    @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Update About information",
     *    @OA\JsonContent(
     *       required={"objective_fr","objective_en","title_en","title_fr","short_description_en","short_description_fr","address","facebook","instagram","linkedin","twitter","email","phone","youtube","image1","image2"},
     *       @OA\Property(property="objective_fr", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="objective_en", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="title_en", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="title_fr", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="short_description_en", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="short_description_fr", type="string", format="text",example="rdc"),
     *       @OA\Property(property="address", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="facebook", type="string", format="text",example="winner kambale"),
     *       @OA\Property(property="instagram", type="string", format="text",example="winner"),
     *       @OA\Property(property="linkedin", type="string", format="text",example="winner"),
     *       @OA\Property(property="twitter", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="email", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="phone", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="youtube", type="string", format="text",example="winner"),
     *       @OA\Property(property="image1", type="string", format="text",example="mpesa"),
     *       @OA\Property(property="image2", type="string", format="text",example="mpesa")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully updated"
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not found"
     * )
     * )
     */
    public function updateAbout(Request $request, $id)
    {
        $about = About::find($id);
        if (!$about) {
            return response()->json(['message' => 'not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_fr' => 'required',
            'short_description_en' => 'required',
            'short_description_fr' => 'required',
            'address' => 'required',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'linkedin' => 'nullable',
            'twitter' => 'nullable',
            'youtube' => 'nullable',
            'email' => 'required',
            'phone' => 'required',
            'objective_fr' => 'required',
            'objective_en' => 'required',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données envoyées ne sont pas valides.',
                'errors' => $validator->errors()
            ], 422);
        }

        $about->title_en = $request->title_en;
        $about->title_fr = $request->title_fr;
        $about->short_description_en = $request->short_description_en;
        $about->short_description_fr = $request->short_description_fr;
        $about->address = $request->address;
        $about->facebook = $request->facebook;
        $about->instagram = $request->instagram;
        $about->twitter = $request->twitter;
        $about->linkedin = $request->linkedin;
        $about->email = $request->email;
        $about->phone = $request->phone;
        $about->objective_fr = $request->objective_fr;
        $about->objective_en = $request->objective_en;
        $about->youtube = $request->youtube;
        for ($i = 1; $i <= 2; $i++) {
            if ($request->hasFile("image$i")) {
                $file = $request->file("image$i");
                $path = $file->store('images/About', 'public');
                $about->{"image$i"} = $path;
            }
        }
        $about->save();
        return response()->json(['message' => 'Successfully updated', 'success' => true, 'status' => 200]);
    }
    /**
     * @OA\Delete(
     * path="/api/deleteAbout/{id}",
     * summary="Delete",
     * description="Delete existing About entry",
     * security={{ "bearerAuth":{ }}},
     * operationId="deleteAbout",
     * tags={"About"},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="ID of the About entry to delete",
     *    @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully deleted"
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not found"
     * )
     * )
     */
    public function deleteAbout($id)
    {
        $about = About::find($id);
        if (!$about) {
            return response()->json(['message' => 'About entry not found'], 404);
        }
        for ($i = 1; $i <= 5; $i++) {
            if ($about->{"image$i"}) {
                Storage::disk('public')->delete($about->{"image$i"});
            }
        }
        $about->delete();
        return response()->json(['message' => 'Successfully deleted', 'success' => true, 'status' => 200]);
    }

    public function getCountgetDashboard()
    {
        $contact = Contact::count();
        $event = Events::count();
        $temoignage = Temoignage::count();
        $projets = Project::count();

        $result = [
            'message' => "success",
            'success' => true,
            'status' => 200,
            'contact' => $contact,
            'events' => $event,
            'temoignage' => $temoignage,
            'project' => $projets
        ];
        return response()->json($result);

    }
}
