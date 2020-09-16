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
                return response()->json(['message' => 'Agenda actualizada'], 201);
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

            return response()->json(['message' => 'Agenda actualizada'], 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }
}
