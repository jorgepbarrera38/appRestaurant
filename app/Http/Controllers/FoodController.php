<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Food;

class FoodController extends Controller
{
    public function index(){
        $findName = request('name');
        $foods = Food::name($findName)->orderBy('id', 'desc')->paginate(10);
        return $foods;
    }
    public function show($id){
        $food = Food::findOrFail($id);
        return $food;
    }
    public function store(Request $request){
        $foodData = $request->validate([
            'name' => 'required|max:50',
            'description' => 'required|max:150',
            'price' => 'required|numeric'
        ], [], [
            'name' => 'Nombre',
            'description' => 'Descripción',
            'price' => 'Precio'
        ]);
        Food::create($foodData);
    }
    public function update(Request $request, $id){
        $food = $request->validate([
            'name' => 'required|max:50',
            'description' => 'required|max:150',
            'price' => 'required|numeric'
        ], [], [
            'name' => 'Nombre',
            'description' => 'Descripción',
            'price' => 'Precio'
        ]);
        Food::findOrFail($id)->update($food);
    }
    public function destroy($id){
        Food::findOrFail($id)->delete();
    }
}
