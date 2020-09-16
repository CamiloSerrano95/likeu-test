<?php

namespace App\Repository\Schedule;

interface ScheduleRepositoryInterface
{
    public function all();
    public function store($data);
    public function getById($schedule_id);
    public function update($schedule_id, $data);
    public function updateStatus($schedule_id, $data);
    public function delete($schedule_id);
}
