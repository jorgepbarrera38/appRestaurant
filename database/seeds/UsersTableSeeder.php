<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'names' => 'Administrador',
            'name' => 'administrador',
            'password' => bcrypt('administrador'),
            'role' => 'administrador'
        ]);
        //factory('App\User', 30)->create();
    }
}
