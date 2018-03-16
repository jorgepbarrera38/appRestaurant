<?php

namespace App\Http\Controllers;
use App\Foodtabletemp;

use Illuminate\Http\Request;
use App\Table;

class SaleController extends Controller
{
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
}
