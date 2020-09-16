<?php

namespace App\Repository\Schedule;

use App\Schedule;

class ScheduleRepository implements ScheduleRepositoryInterface {
    private $model;

    public function __construct(Schedule $schedule) {
        $this->model = $schedule;
    }

    public function all() {
        return $this->model->all();
    }

    public function store($data) {
        $schedule = $this->model->insert($data);

        if (!$schedule) {
            throw new \Exception('No se pudo crear la agenda');
        }
        return $schedule;
    }

    public function getById($schedule_id) {
        return $this->model->findOrFail($schedule_id);
    }

    public function update($schedule_id, $data) {
        return $this->model->where('id', $schedule_id)->where('status', 'Programada')->update($data);
    }

    public function delete($schedule_id) {
        return $this->model->findOrFail($schedule_id)->delete();
    }

    public function updateStatus($schedule_id, $data) {
        return $this->model->findOrFail($schedule_id)->update($data);
    }
}
