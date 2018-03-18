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
        factory(App\Food::class, 300)->create();
        /*
        Food::create([
            'name' => 'Hamburguesa de carne',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin leo sem, molestie et semper id',
            'price' => 5500
        ]);
        Food::create([
            'name' => 'Perro caliente americano',
            'description' => 'Nunc eleifend euismod dolor, suscipit eleifend nulla interdum non. Mauris porttitor venenatis elit sed finibus. Suspendisse tincidunt eu',
            'price' => 4000
        ]);
        Food::create([
            'name' => 'Tacos',
            'description' => 'Nam id ullamcorper lacus. Aliquam erat volutpat. Sed a fringilla felis, a aliquam',
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
        ]);*/
    }
}
