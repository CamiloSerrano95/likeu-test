<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repository\Schedule\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ScheduleController extends Controller
{
    private $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository) {
        $this->scheduleRepository = $scheduleRepository;
        $this->middleware('jwt.verify');
    }

    public function all(){
        $schedules = $this->scheduleRepository->all();
        return response()->json($schedules, 200);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|numeric|exists:clients,id',
                'subject' => 'required|regex:/^[a-zA-Z\s]*$/',
                'date_time' => 'required|date_format:Y-m-d H:i',
                'status' => 'alpha'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $data = $request->all();
            $this->scheduleRepository->store($data);

            return response()->json(['message' => 'Agenda creada exitosamente'], 201);
        } catch (\Exception $exception) {
            return response()->json([$exception->getMessage()], 400);
        }
    }

    public function getById($schedule_id) {
        try {
            $schedule = $this->scheduleRepository->getById($schedule_id);
            return response()->json($schedule, 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function update($schedule_id, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|numeric|exists:clients,id',
                'subject' => 'required|regex:/^[a-zA-Z\s]*$/',
                'date_time' => 'required|date_format:Y-m-d H:i',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $data = $request->all();
            $schedule = $this->scheduleRepository->update($schedule_id, $data);

            if ($schedule == 1) {
                return response()->json(['message' => 'Agenda actualizada exitosamente'], 201);
            } else {
                return response()->json(['message' => 'No se pudo actualizar la agenda, el estado no es Programada'], 201);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function destroy($schedule_id) {
        try {
            $this->scheduleRepository->delete($schedule_id);
            return response()->json(['message' => 'Agenda eliminada'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function updateStatus($schedule_id, Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|alpha'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $data = $request->all();
            $this->scheduleRepository->update($schedule_id, $data);

            return response()->json(['message' => 'Estado de la agenda actualizada exitosamente'], 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }
}

/**
* @OA\Get(
* path="/api/schedule/all",
* summary="Obtener todas las agendas",
* description="Obtener todas las agendas",
* operationId="schedule-all",
* tags={"Agendas"},
* security={{ "bearer_token": {} }},
* @OA\Response(
*    response=200,
*    description="Todos las agendas",
*    @OA\JsonContent(
*      @OA\Property(property="id", type="integer"),
*      @OA\Property(property="client_id", type="integer"),
*      @OA\Property(property="subject", type="string"),
*      @OA\Property(property="date_time", type="string"),
*      @OA\Property(property="status", type="string"),
*    )
* ),
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
*)
*/

/**
* @OA\Get(
* path="/api/schedule/{id}",
* summary="Obtener informacion de una agenda por id",
* description="Obtener informacion de una agenda por id",
* operationId="schedule-id",
* tags={"Agendas"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id de la agenda",
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
*    description="Agenda por id",
*    @OA\JsonContent(
*      @OA\Property(property="id", type="integer"),
*      @OA\Property(property="client_id", type="integer"),
*      @OA\Property(property="subject", type="string"),
*      @OA\Property(property="date_time", type="string"),
*      @OA\Property(property="status", type="string")
*    )
* ),
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
*)
*/

/**
* @OA\Post(
* path="/api/schedule/create",
* summary="Crear una agenda",
* description="Crear una agenda",
* operationId="schedule-create",
* tags={"Agendas"},
* security={{ "bearer_token": {} }},
* @OA\RequestBody(
*    required=true,
*    description="Datos para crear una agenda",
*    @OA\JsonContent(
*      required={"client_id","subject","date_time", "status"},
*      @OA\Property(property="client_id", type="integer"),
*      @OA\Property(property="subject", type="string"),
*      @OA\Property(property="date_time", type="string"),
*      @OA\Property(property="status", type="string"),
*    ),
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Agenda creada exitosamente")
*    )
* ),
*   @OA\Response(
*       response=400,
*       description="Errores de validacion",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="The given data was invalid."),
*           @OA\Property(property="errors", type="object")
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
*)
*/

/**
* @OA\Put(
* path="/api/schedule/update/{id}",
* summary="Editar una agenda",
* description="Editar un agenda",
* operationId="schedule-update",
* tags={"Agendas"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id de la agenda",
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
*    description="Datos para actualizar una agenda",
*    @OA\JsonContent(
*      required={"client_id","subject","date_time", "status"},
*      @OA\Property(property="client_id", type="integer"),
*      @OA\Property(property="subject", type="string"),
*      @OA\Property(property="date_time", type="string"),
*      @OA\Property(property="status", type="string"),
*    ),
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Agenda actualizada exitosamente")
*    )
* ),
*   @OA\Response(
*       response=400,
*       description="Errores de validacion",
*       @OA\JsonContent(
*           @OA\Property(property="message", type="string", example="The given data was invalid."),
*           @OA\Property(property="errors", type="object")
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
*)
*/

/**
* @OA\Delete(
* path="/api/schedule/delete/{id}",
* summary="Eliminar una agenda",
* description="Eliminar una agenda",
* operationId="schedule-delete",
* tags={"Agendas"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id de la agenda",
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
*      @OA\Property(property="message", type="string", example="Agenda eliminada")
*    )
* ),
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
* @OA\Put(
* path="/api/schedule/update-status/{id}",
* summary="Cambiar estado de una agenda",
* description="Cambiar estado de una agenda",
* operationId="schedule-update-status",
* tags={"Agendas"},
* security={{ "bearer_token": {} }},
* @OA\Parameter(
*    description="Id de la agenda",
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
*    description="Datos para actualizar el estado de una agenda",
*    @OA\JsonContent(
*      required={"status"},
*      @OA\Property(property="status", type="string"),
*    ),
* ),
* @OA\Response(
*    response=201,
*    description="Estado del proceso",
*    @OA\JsonContent(
*      @OA\Property(property="message", type="string", example="Estado de la agenda actualizada exitosamente")
*    )
* ),
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
