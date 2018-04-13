<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Table;
use App\Food;
use App\Foodtabletemp;
use App\Sale;
use App\Saledetail;

class SaleController extends Controller
{
    public function getTables(){
        $tables = Table::with('foodtabletemps.food')->where('active', true)->get();
        return $tables;
    }
    public function getfoods(Request $request){
        $nameFind = $request['name'];
        return Food::name($nameFind)->get();
    }
    public function store(Request $request){
       $request->validate([
           'table' => 'required',
           'foods' => 'required',
       ]);
       $table = $request->input('table');
       $foods = $request->input('foods');
       foreach($foods as $food){
            $foodtabletemp = new Foodtabletemp();
            $foodtabletemp->food_id = $food['foodId'];
            $foodtabletemp->quantity = $food['foodQuantity'];
            $foodtabletemp->table_id = $table;
            $foodtabletemp->save();
       }
       return "ok";
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
            $prices += $food->food->price*$food->quantity;
        }
        return $prices;
    }
    public function destroy($id){
        Foodtabletemp::findOrFail($id)->delete();
    }
    public function paynow(Request $request){
        //Obteniendo todas las comidas temporales de la mesa
        $foodsTempInTable = Foodtabletemp::where('table_id', $request->input('tableId'))->get();
        //Eliminando todas las comidas temporales de la mesa
        Foodtabletemp::where('table_id', $request->input('tableId'))->delete();
        //Cambiando el estado de la mesa a libre
        Table::findOrFail($request->input('tableId'))->update(['state'=>true]);


        //Guardando la venta de la mesa en Sale
        //Obteniendo el precio total
        $priceTotal = 0;
        foreach ($foodsTempInTable as $food) {
            $priceTotal += $food->food->price*$food->quantity;
        }
        $sale = new Sale();
        $sale->table_id = $request->input('tableId');
        $sale->pricetotal = $priceTotal;
        $sale->save();

        //Guardando los detalles de la venta
        foreach($foodsTempInTable as $foodTemp){
            $saledetail = new Saledetail();
            $saledetail->sale_id = $sale->id;
            $saledetail->food_id = $foodTemp->food_id;
            $saledetail->foodprice = $foodTemp->food->price;
            $saledetail->quantity = $foodTemp->quantity;
            $saledetail->pricetotal = $foodTemp->food->price * $foodTemp->quantity;
            $saledetail->save();
        }
    }
    public function abortbuy($id){
        $foodtabletemp = Foodtabletemp::where('table_id', $id)->delete();
        $table = Table::findOrFail($id)->update(['state'=>true]);
    }
}
