<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
        $this->middleware('jwt.verify');
    }

    public function all(){
        $users = $this->userRepository->all();
        return response()->json($users, 200);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|regex:/^[a-zA-Z\s]*$/',
                'email' => 'required|email|unique:users,email,NULL,id',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $data = $request->all();
            $data['password'] = $this->userRepository->hashPassword($data['password']);

            $this->userRepository->store($data);

            return response()->json(['message' => 'Usuario creado exitosamente'], 201);
        } catch (\Exception $exception) {
            return response()->json([$exception->getMessage()], 400);
        }
    }

    public function getById($user_id) {
        try {
            $user = $this->userRepository->getById($user_id);
            return response()->json($user, 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function update($user_id, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|regex:/^[a-zA-Z\s]*$/',
                'email' => "required|email|unique:users,email,{$user_id},id",
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $data = $request->all();

            if ($request->password) {
                $data['password'] = $this->userRepository->hashPassword($data['password']);
            } else {
                unset($data['password']);
            }

            $this->userRepository->update($user_id, $data);
            return response()->json(['message' => 'Usuario actualizado'], 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function destroy($user_id) {
        try {
            $this->userRepository->delete($user_id);
            return response()->json(['message' => 'Usuario eliminado'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }
}

/**
* @OA\Get(
* path="/api/user/all",
* summary="Obtener todos los usuarios",
* description="Obtener todos los usuarios",
* operationId="user-all",
* tags={"Usuarios"},
* security={{ "bearer_token": {} }},
* @OA\Response(
*    response=200,
*    description="Todos los usuarios",
*    @OA\JsonContent(
*      @OA\Property(property="id", type="integer"),
*      @OA\Property(property="name", type="string"),
*      @OA\Property(property="email", type="string")
*    )
* ),
* @OA\Response(
*    response=401,
*    description="Accion invalida",
*    @OA\JsonContent(
*       @OA\Property(property="message", type="string", example="Unauthorized")
*       )
*     )
* )
*/

/**
* @OA\Get(
* path="/api/user/{id}",
* summary="Obtener informacion de un usuario por id",
* description="Obtener informacion de un usuario por id",
* operationId="user-id",
* tags={"Usuarios"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id del usuario",
*    in="path",
*    name="id",
*    required=true,
*    example="1",
*    @OA\Schema(
*       type="integer",
*       format="int64"
*    )
* ),
* @OA\Response(
*    response=200,
*    description="Usuario por id",
*    @OA\JsonContent(
*      @OA\Property(property="id", type="integer"),
*      @OA\Property(property="name", type="string"),
*      @OA\Property(property="email", type="string")
*    )
* ),
* @OA\Response(
*    response=401,
*    description="Accion invalida",
*    @OA\JsonContent(
*       @OA\Property(property="message", type="string", example="Unauthorized")
*       )
*     )
* )
*/

/**
* @OA\Post(
* path="/api/user/create",
* summary="Crear un usuario",
* description="Crear un usuario",
* operationId="user-create",
* tags={"Usuarios"},
* security={{ "bearer_token": {} }},
* @OA\RequestBody(
*    required=true,
*    description="Datos para crear un usuario",
*    @OA\JsonContent(
*       required={"name","email","password"},
*       @OA\Property(property="name", type="string"),
*       @OA\Property(property="email", type="string", format="email"),
*       @OA\Property(property="password", type="string", format="password"),
*    ),
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Usuario creado exitosamente")
*    )
* ),
* @OA\Response(
*    response=401,
*    description="Accion no permitida",
*    @OA\JsonContent(
*       @OA\Property(property="message", type="string", example="Unauthorized")
*       )
*     )
* )
*/

/**
* @OA\Put(
* path="/api/user/update/{id}",
* summary="Editar un usuario",
* description="Editar un usuario",
* operationId="user-update",
* tags={"Usuarios"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id del usuario",
*    in="path",
*    name="id",
*    required=true,
*    example="1",
*    @OA\Schema(
*       type="integer",
*       format="int64"
*    )
* ),
* @OA\RequestBody(
*    required=true,
*    description="Datos para actualizar un usuario",
*    @OA\JsonContent(
*       required={"name","email","password"},
*       @OA\Property(property="name", type="string"),
*       @OA\Property(property="email", type="string", format="email"),
*       @OA\Property(property="password", type="string", format="password"),
*    ),
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Usuario actualizado exitosamente")
*    )
* ),
* @OA\Response(
*    response=401,
*    description="Accion no permitida",
*    @OA\JsonContent(
*       @OA\Property(property="message", type="string", example="Unauthorized")
*       )
*     )
* )
*/

/**
* @OA\Delete(
* path="/api/user/delete/{id}",
* summary="Eliminar un usuario",
* description="Eliminar un usuario",
* operationId="user-delete",
* tags={"Usuarios"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id del usuario",
*    in="path",
*    name="id",
*    required=true,
*    example="1",
*    @OA\Schema(
*       type="integer",
*       format="int64"
*    )
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Usuario eliminado")
*    )
* ),
* @OA\Response(
*    response=401,
*    description="Accion no permitida",
*    @OA\JsonContent(
*       @OA\Property(property="message", type="string", example="Unauthorized")
*       )
*     )
* )
*/
