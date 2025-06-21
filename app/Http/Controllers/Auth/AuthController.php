<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;
use Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/auth/login",
     * summary="Create",
     * description="Creation",
     * security={{ "bearerAuth":{ }}},
     * operationId="login",
     * tags={"Authenticate"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enregistrer",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="text", example="winner@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text",example="12345"),
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
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Verifier tous les champs.',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Verifier votre authentification svp'], 421);
        }
        $device_name = '';
        if ($user->isTablet()) {
            $device_name = 'tablet';
        } elseif ($user->isDesktop()) {
            $device_name = 'desktop';
        } elseif ($user->isMobile()) {
            $device_name = 'mobile';
        }

        $token = $user->createToken($device_name, ['*'])->plainTextToken;
        return $this->responseWithToken($token, $user);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/logout",
     *     summary="Deconnexion",
     *     description="Deconnexion d'un utilisateur en utilisant son token",
     *     security={{"bearerAuth":{}}},
     *     operationId="logout",
     *     tags={"Authenticate"},
     *     @OA\Response(
     *         response=200,
     *         description="Vous etes deconecte",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Vous etes deconecte")
     *         )
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Logged out successfully.',
            'status' => 200
        ]);
    }
    private function responseWithToken($token, $user)
    {
        return Response::json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'message' => 'Vous êtes bien connecté sur notre app'
        ]);
    }
}
