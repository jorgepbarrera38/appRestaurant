<?php

namespace App\Http\Controllers;

use App\Report;
use App\Sale;
use App\Saledetail;
use App\Food;
use App\Expend;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$saleDetails = Saledetail::all();
        $total = $saleDetails->groupBy('food_id')->transform(function($value, $key){
            return ['food_id'=>$key, 'cant'=>$value->count()];
        });
        return $total->sortByDesc('cant');
        ///*/
        //Validando la data
        /*$data = $request->validate([
            'datefrom' =>'required',
            'dateto' =>'required'
        ]);
        //Convirtiendo las fechas
        $date1 = new Carbon($request->input('datefrom'));
        $date2 = new Carbon($request->input('dateto'));
        //Consultando
        $salesId = [];
        $sales = Sale::with('table')->whereBetween('created_at' ,array($date1, $date2->addHours(23)->addMinutes(59)))->get();

        //$sales = Sale::all();
        foreach ($sales as $sale) {
            array_push($salesId, $sale->id);
        }
        //return $salesId;
        $saleDetailsFound = [];
        foreach ($salesId as $saleId) {
           array_push($saleDetailsFound, Saledetail::where('sale_id', $saleId)->get());
        }
        $collectionSaleDetailsFound = collect($saleDetailsFound);
        $final = $collectionSaleDetailsFound->flatten();
        $uff = $final->groupBy('food_id')->transform(function($value, $key){
            return ['food_id'=>$key, 'cant'=>$value->count()];
        });

        $foodsMostSold = $uff->sortByDesc('cant');
        $foodsMostSoldFinal = [];
        foreach ($foodsMostSold as $foodMost) {
            $foodTemp = Food::where('id', $foodMost['food_id'])->first();
            array_push($foodsMostSoldFinal, ['name'=>$foodTemp->name, 'cant'=>$foodMost['cant']]);
        }
        //Obteniendo los gastos
        $date1 = new Carbon($request->input('datefrom'));
        $date2 = new Carbon($request->input('dateto'));
        $expends = Expend::where('date','>=',$request->input('datefrom'))->where('date', '<=', $request->input('dateto'))->get();
        return response()->json(['sales'=>$sales, 'expends'=>$expends]);*/
    }
    public function sales(Request $request){
        $data = $request->validate([
            'datefrom' =>'required',
            'dateto' =>'required'
        ]);
        //Convirtiendo las fechas
        $date1 = new Carbon($request->input('datefrom'));
        $date2 = new Carbon($request->input('dateto'));

        $salesPriceTotal = Sale::with('table')->whereBetween('created_at' ,array($date1, $date2->addHours(23)->addMinutes(59)))->get();
        $total = 0;
        foreach ($salesPriceTotal as $sale) {
            $total += $sale->pricetotal;
        }
        //Consultando
        $sales = Sale::with('table')->whereBetween('created_at' ,array($date1, $date2->addHours(23)->addMinutes(59)))->orderBy('created_at', 'DESC')->paginate(8);
        return response()->json(['sales'=>$sales, 'total'=>$total],200);
    }
    public function expends(Request $request){
        $data = $request->validate([
            'datefrom' =>'required',
            'dateto' =>'required'
        ]);
        $total = 0;
        $expendsPriceTotal = Expend::where('date','>=',$request->input('datefrom'))->where('date', '<=', $request->input('dateto'))->get();
        foreach($expendsPriceTotal as $expend){
            $total += $expend->val;
        }
        $expends = Expend::where('date','>=',$request->input('datefrom'))->where('date', '<=', $request->input('dateto'))->paginate(8);
        return response()->json(['expends'=>$expends, 'total'=>$total], 200);
    }
    public function foodmostsold(Request $request){
        $dateFrom = new Carbon($request->input('datefrom'));
        $dateTo = new Carbon($request->input('dateto'));
        $dateTo->addHours(23)->addMinutes(59)->addSeconds(59);

        $saleDetail = Saledetail::with('food')->whereBetWeen('created_at', [$dateFrom, $dateTo])->get();
        $foodsGroup = $saleDetail->groupBy('food_id');  
        $foodsQuantity = [];
        foreach($foodsGroup as $foodGroup){
            $quantityFood = 0;
            $foodId;
            $foodName;
            foreach($foodGroup as $food){
                $quantityFood+=$food->quantity;
                $foodId = $food->food_id;
                $foodName = $food->food->name;
            }
            array_push($foodsQuantity, ['food_id'=>$foodId,'food'=>$foodName, 'quantity'=>$quantityFood]);
        }
        return $foodsQuantity;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $saleDetails = Saledetail::with('food')->where('sale_id', $id)->get();
        return $saleDetails;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
