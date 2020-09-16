<?php

namespace App\Repository\User;

interface UserRepositoryInterface
{
    public function all();
    public function store($data);
    public function getById($user_id);
    public function update($user_id, $data);
    public function delete($user_id);
}
