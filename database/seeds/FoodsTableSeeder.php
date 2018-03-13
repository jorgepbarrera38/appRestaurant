<?php

use Illuminate\Database\Seeder;
use App\Food;
class FoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Food::create([
            'name' => 'Hamburguesa de carne',
            'description' => 'Deliciosa hamburguesa con carne normal',
            'price' => 5500
        ]);
        Food::create([
            'name' => 'Perro caliente americano',
            'description' => 'Suculento perro con salchicha americana de la buena',
            'price' => 4000
        ]);
    }
}
