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
        Food::create([
            'name' => 'Tacos',
            'description' => 'ESto hace que sea habitualmente calificada ',
            'price' => 4800
        ]);
        Food::create([
            'name' => 'Pollo frito',
            'description' => 'Frecuente que los establecimientos "inviten" a los clien',
            'price' => 6000
        ]);
        Food::create([
            'name' => 'Tacos al pastor',
            'description' => 'Consistencia y para preservar frescura. Esto requiere un alto grado de ingenie',
            'price' => 6000
        ]);
    }
}
