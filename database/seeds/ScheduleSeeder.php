<?php

use App\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedules = [
            [
                'client_id' => 1,
                'subject'   => 'Revision de pruebas',
                'date_time' => '2020-09-18 12:00',
                'status'    => 'Programada'
            ],
            [
                'client_id' => 2,
                'subject'   => 'Revision de examenes',
                'date_time' => '2020-09-19 12:00',
                'status'    => 'Programada'
            ],
            [
                'client_id' => 3,
                'subject'   => 'Revision de tesis',
                'date_time' => '2020-09-20 12:00',
                'status'    => 'Programada'
            ]
        ];

        Schedule::insert($schedules);
    }
}
