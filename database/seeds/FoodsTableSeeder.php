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
        //factory(App\Food::class, 300)->create();
        
        Food::create([
            'name' => 'Ensalada Carmen',
            'description' => 'Lechugas mixtas de finca Del Campo, palmitos frescos del Putumayo, cítricos, mango, quinoa tostada, vinagreta de arazá amazónica',
            'price' => 20000
        ]);
        Food::create([
            'name' => 'Langosta',
            'description' => 'Sellada en mantequilla avellenada, garam masala, puré de coliflor rostizada al azafrán, tatsoi, vinagreta de cítricos.',
            'price' => 35000
        ]);
        Food::create([
            'name' => 'Vino de Uva',
            'description' => 'Vino con sabor a uva',
            'price' => 30000
        ]);
        Food::create([
            'name' => 'Cangrejo',
            'description' => 'Ensalada de centolla & vadouvan caribeño, aguacate, masa soplada, dashi de cangrejo, perlas negras, mandarina & jengibre',
            'price' => 40000
        ]);
        Food::create([
            'name' => 'Ceviche laminado',
            'description' => 'Pesca del día, leche de tigre de corozo costeño, pepino comprimido, shio kombu,copoazú, humo de coco carbonizado',
            'price' => 35000
        ]);
        Food::create([
            'name' => 'Agua',
            'description' => 'Agua natural',
            'price' => 3000
        ]);
        Food::create([
            'name' => 'Carimañola',
            'description' => 'Yuca & achiote, centro liquido de foie gras & trufas negras, salsa bigarade de temporada, suero costeño',
            'price' => 25000
        ]);
        Food::create([
            'name' => 'Atollado',
            'description' => 'Arroz meloso con bisque de crustáceos, camarones, almejas negras, chicharrón, ensaladilla de arvejas, vinagreta de tamarindo, hogao',
            'price' => 25000
        ]);
        Food::create([
            'name' => 'Turrón de chocolate',
            'description' => 'Chocolate Santander, tierra de chocolate, helado de coco & limón, espuma de copoazú',
            'price' => 18000
        ]);
        Food::create([
            'name' => 'Tacos coreanos',
            'description' => 'Lonjas de res marinadas al estilo coreano, selladas a la plancha, kimchi picante de repollo morado & zanahoria, peras asiáticas, cogollo asado, tortillas de cilantro & maíz',
            'price' => 27000
        ]);
        Food::create([
            'name' => 'Bebida jugo natural',
            'description' => 'Delicioso jugo natural en agua o leche de varios sabores',
            'price' => 5000
        ]);
    }
}
