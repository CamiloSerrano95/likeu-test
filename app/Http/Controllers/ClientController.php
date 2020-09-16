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
            return response()->json(['message' => 'Cliente actualizado'], 201);
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
