<?php

namespace App\Repository\User;

use App\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface {
    private $model;

    public function __construct(User $user) {
        $this->model = $user;
    }

    public function all() {
        return $this->model->all();
    }

    public function store($data) {
        $user = $this->model->insert($data);

        if (!$user) {
            throw new \Exception('No se pudo crear el usuario');
        }
        return $user;
    }

    public function getById($user_id) {
        return $this->model->findOrFail($user_id);
    }

    public function update($user_id, $data) {
        return $this->model->findOrFail($user_id)->update($data);
    }

    public function delete($user_id) {
        return $this->model->findOrFail($user_id)->delete();
    }

    public function hashPassword($password) {
        return Hash::make($password);
    }
}
