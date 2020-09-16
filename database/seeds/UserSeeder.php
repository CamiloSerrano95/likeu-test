<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert(
            [
                'name'     => 'Likeu Test',
                'email'    => 'testphp@likeu.com',
                'password' => Hash::make('123456'),
            ]
        );
    }
}
