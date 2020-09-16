<?php

namespace App\Repository\Client;

use App\Client;

class ClientRepository implements ClientRepositoryInterface {
    private $model;

    public function __construct(Client $client) {
        $this->model = $client;
    }

    public function all() {
        return $this->model->all();
    }

    public function store($data) {
        $client = $this->model->insert($data);

        if (!$client) {
            throw new \Exception('No se pudo crear el cliente');
        }
        return $client;
    }

    public function getById($client_id) {
        return $this->model->findOrFail($client_id);
    }

    public function update($client_id, $data) {
        return $this->model->findOrFail($client_id)->update($data);
    }

    public function delete($client_id) {
        return $this->model->findOrFail($client_id)->delete();
    }
}
