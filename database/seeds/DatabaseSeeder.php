<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FoodsTableSeeder::class);
        $this->call(TablesTableSeeder::class);
        App\User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('admin')

        ]);
        //factory('App\Food', 50)->create();

        //factory('App\Sale', 10)->create();
        /*
        $foods=[];
        for ($i=0; $i < 100; $i++) { 
            $foodId = App\Food::all()->random()->id;
            $foodPrice = App\Food::find($foodId)->price;
            array_push($foods, ['sale_id'=>1,'food_id'=>$foodId, 'foodprice'=>$foodPrice, 'quantity'=>1, 'pricetotal'=>$foodPrice]);
        }  
        foreach ($foods as $food) {
            \App\Saledetail::create($food);
        }*/

    }
}
