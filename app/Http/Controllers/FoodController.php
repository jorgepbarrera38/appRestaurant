<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Food;

class FoodController extends Controller
{
    public function store(Request $request){
        $foodData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric'
        ], [], [
            'name' => 'Nombre',
            'Descripción',
            'price' => 'Precio'
        ]);
        Food::create($foodData);
    }
}
