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
