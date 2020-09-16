<?php

namespace App\Repository\Client;

interface ClientRepositoryInterface
{
    public function all();
    public function store($data);
    public function getById($client_id);
    public function update($client_id, $data);
    public function delete($client_id);
}
