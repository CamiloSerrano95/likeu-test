<?php

use App\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'name'           => 'Camilo Serrano',
                'identification' => '12654789',
                'contact_number' => '3135135078',
                'date_birth'     => '1995-08-24'
            ],
            [
                'name'           => 'Fabio Serrano',
                'identification' => '12047891',
                'contact_number' => '3135478964',
                'date_birth'     => '1999-07-25'
            ],
            [
                'name'           => 'Antonella Serrano',
                'identification' => '50897210',
                'contact_number' => '3006798512',
                'date_birth'     => '2003-06-15'
            ]
            ];

        Client::insert($clients);
    }
}
