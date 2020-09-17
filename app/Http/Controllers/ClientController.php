<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repository\Client\ClientRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientController extends Controller
{
    private $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository) {
        $this->clientRepository = $clientRepository;
        $this->middleware('jwt.verify');
    }

    public function all(){
        $users = $this->clientRepository->all();
        return response()->json($users, 200);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|regex:/^[a-zA-Z\s]*$/',
                'identification' => 'required|unique:clients|numeric',
                'contact_number' => 'required|numeric',
                'date_birth' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $data = $request->all();

            $this->clientRepository->store($data);

            return response()->json(['message' => 'Cliente creado exitosamente'], 201);
        } catch (\Exception $exception) {
            return response()->json([$exception->getMessage()], 400);
        }
    }

    public function getById($client_id) {
        try {
            $client = $this->clientRepository->getById($client_id);
            return response()->json($client, 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function update($client_id, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|regex:/^[a-zA-Z\s]*$/',
                'identification' => "required|unique:clients,identification,{$client_id},id|numeric",
                'contact_number' => 'required|numeric',
                'date_birth' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $data = $request->all();

            $this->clientRepository->update($client_id, $data);
            return response()->json(['message' => 'Cliente actualizado exitosamente'], 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function destroy($client_id) {
        try {
            $this->clientRepository->delete($client_id);
            return response()->json(['message' => 'Usuario eliminado'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }
}

/**
* @OA\Get(
* path="/api/client/all",
* summary="Obtener todos los clientes",
* description="Obtener todos los clientes",
* operationId="client-all",
* tags={"Clientes"},
* security={{ "bearer_token": {} }},
* @OA\Response(
*    response=200,
*    description="Todos los clientes",
*    @OA\JsonContent(
*      @OA\Property(property="id", type="integer"),
*      @OA\Property(property="name", type="string"),
*      @OA\Property(property="identification", type="string"),
*      @OA\Property(property="contact_number", type="string"),
*      @OA\Property(property="date_birth", type="string")
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
* path="/api/client/{id}",
* summary="Obtener informacion de un cliente por id",
* description="Obtener informacion de un cliente por id",
* operationId="client-id",
* tags={"Clientes"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id del cliente",
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
*    description="Cliente por id",
*    @OA\JsonContent(
*      @OA\Property(property="id", type="integer"),
*      @OA\Property(property="name", type="string"),
*      @OA\Property(property="identification", type="string"),
*      @OA\Property(property="contact_number", type="string"),
*      @OA\Property(property="date_birth", type="string")
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
* path="/api/client/create",
* summary="Crear un cliente",
* description="Crear un cliente",
* operationId="client-create",
* tags={"Clientes"},
* security={{ "bearer_token": {} }},
* @OA\RequestBody(
*    required=true,
*    description="Datos para crear un usuario",
*    @OA\JsonContent(
*       required={"name","identification","contact_number", "date_birth"},
*       @OA\Property(property="name", type="string"),
*       @OA\Property(property="identification", type="string"),
*       @OA\Property(property="contact_number", type="string"),
*       @OA\Property(property="date_birth", type="string")
*    ),
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Cliente creado exitosamente")
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
* path="/api/client/update/{id}",
* summary="Editar un cliente",
* description="Editar un cliente",
* operationId="client-update",
* tags={"Clientes"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id del cliente",
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
*    description="Datos para actualizar un cliente",
*    @OA\JsonContent(
*       required={"name","identification","contact_number", "date_birth"},
*       @OA\Property(property="name", type="string"),
*       @OA\Property(property="identification", type="string"),
*       @OA\Property(property="contact_number", type="string"),
*       @OA\Property(property="date_birth", type="string")
*    ),
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Cliente actualizado exitosamente")
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
* path="/api/client/delete/{id}",
* summary="Eliminar un cliente",
* description="Eliminar un cliente",
* operationId="client-delete",
* tags={"Clientes"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id del cliente",
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
*      @OA\Property(property="message", type="string", example="Cliente eliminado")
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
