<?php

namespace App\Http\Controllers;

use App\Expend;
use Illuminate\Http\Request;

class ExpendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $expends = Expend::orderBy('id', 'DESC')->get();
        return $expends;
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
        $data = $request->validate([
            'date' => 'required|date',
            'val' => 'required|numeric',
            'detail' => 'required|max:100',
            ], [], [
                'date' => 'Fecha',
                'val' => 'Valor',
                'detail' => 'Detalle',
            ]);
        Expend::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expend  $expend
     * @return \Illuminate\Http\Response
     */
    public function show(Expend $expend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expend  $expend
     * @return \Illuminate\Http\Response
     */
    public function edit(Expend $expend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expend  $expend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expend $expend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expend  $expend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expend $expend)
    {
        //
    }
}
