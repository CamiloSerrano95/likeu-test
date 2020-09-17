<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.verify', ['except' => ['login']]);
    }

    public function login() {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Credenciales invalidas, verifique y vuelva a intentarlo'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }
}

/**
* @OA\Post(
*   path="/api/login",
*   summary="Iniciar sesion",
*   description="Login por email y contraseña",
*   operationId="login",
*   tags={"Autenticación"},
*   @OA\RequestBody(
*       required=true,
*       description="Credenciales para inicio de sesion",
*       @OA\JsonContent(
*           required={"email","password"},
*           @OA\Property(property="email", type="string", format="email", example="testphp@likeu.com"),
*           @OA\Property(property="password", type="string", format="password", example="123456"),
*       ),
*   ),
*   @OA\Response(
*       response=200,
*       description="Login con exito",
*       @OA\JsonContent(
*           @OA\Property(property="access_token", type="string"),
*           @OA\Property(property="token_type", type="string", example="bearer"),
*           @OA\Property(property="expires_in", type="string", example="3600")
*       )
*   ),
*   @OA\Response(
*       response=401,
*       description="Credenciales invalidas",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="Credenciales invalidas, verifique y vuelva a intentarlo")
*       )
*    )
* )
*/

/**
* @OA\Post(
*   path="/api/me",
*   summary="Obtener informacion del usuario autenticado",
*   description="Obtener informacion de usuario autenticado",
*   operationId="me",
*   tags={"Autenticación"},
*   security={{ "bearer_token": {} }},
*   @OA\Response(
*       response=200,
*       description="Informacion del usuario",
*       @OA\JsonContent(
*           @OA\Property(property="id", type="integer"),
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="email", type="string")
*       )
*   ),
*   @OA\Response(
*       response=404,
*       description="Token no encontrado en la peticion",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="Authorization Token not found")
*       )
*   ),
*   @OA\Response(
*       response=422,
*       description="Token es invalido",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="Token is Invalid")
*       )
*   ),
*   @OA\Response(
*       response=423,
*       description="Token ha expirado",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="Token is Expired")
*       )
*    )
* )
*/

/**
* @OA\Post(
*   path="/api/logout",
*   summary="Salir de la aplicacion",
*   description="Salir de la aplicacion",
*   operationId="logout",
*   tags={"Autenticación"},
*   security={{ "bearer_token": {} }},
*   @OA\Response(
*       response=200,
*       description="Informacion del usuario",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="Successfully logged out")
*       )
*   ),
*   @OA\Response(
*       response=404,
*       description="Token no encontrado en la peticion",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="Authorization Token not found")
*       )
*    ),
*   @OA\Response(
*       response=422,
*       description="Token es invalido",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="Token is Invalid")
*       )
*   )
* )
*/

