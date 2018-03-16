<?php

use Illuminate\Database\Seeder;
use App\Table;
class TablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Table::create([
            'name' => 'Mesa 1'
        ]);
        Table::create([
            'name' => 'Mesa 2'
        ]);
    }
}
