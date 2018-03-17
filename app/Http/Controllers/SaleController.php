<?php

namespace App\Http\Controllers;
use App\Foodtabletemp;

use Illuminate\Http\Request;
use App\Table;
use App\Food;

class SaleController extends Controller
{
    public function getfoods(Request $request){
        $nameFind = $request['name'];
        return Food::name($nameFind)->paginate(6);
    }
    public function store(Request $request){
        $request->validate([
            'table' => 'required',
            'food' => 'required',
        ]);
        $foodTemp = new Foodtabletemp;
        $foodTemp->table_id = $request->input('table');
        $foodTemp->food_id = $request->input('food');
        $foodTemp->save();
    }
    public function update(Request $request, $id){
        $table = Table::findOrFail($id);
        if($table->state){
            $table->state = false;
        }else{
            $table->state = true;
        }        
        $table->save();
    }
    public function pay(Request $request){
        $foodsTempfromTable = Foodtabletemp::where('table_id', $request->input('tableId'))->with('food')->get();
        $prices=0;
        foreach ($foodsTempfromTable as $food) {
            $prices += $food->food->price;
        }
        return $prices;
    }
}
