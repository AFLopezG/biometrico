<?php

namespace App\Http\Controllers;

use App\Models\PagoExtra;
use App\Http\Requests\StorePagoExtraRequest;
use App\Http\Requests\UpdatePagoExtraRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PagoExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StorePagoExtraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePagoExtraRequest $request)
    {
        //
        $pago=new PagoExtra();
        $pago->fecha=$request->fecha;
        $pago->motivo=$request->motivo;
        $pago->monto=$request->monto;
        $pago->driver_id=$request->driver_id;
        $pago->save();

        return PagoExtra::find($pago->id)->with('driver');

    }

    public function anularExtra(Request $request){
        //return $request;
        $pago=PagoExtra::find($request->pago);
        $pago->anulado=true;
        $pago->motivo=$request->motivo;
        $pago->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PagoExtra  $pagoExtra
     * @return \Illuminate\Http\Response
     */
    public function show(PagoExtra $pagoExtra)
    {
        //
    }
    public function consultachofer(Request $request){
        return PagoExtra::with('driver')->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PagoExtra  $pagoExtra
     * @return \Illuminate\Http\Response
     */
    public function edit(PagoExtra $pagoExtra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePagoExtraRequest  $request
     * @param  \App\Models\PagoExtra  $pagoExtra
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePagoExtraRequest $request, PagoExtra $pagoExtra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PagoExtra  $pagoExtra
     * @return \Illuminate\Http\Response
     */
    public function destroy(PagoExtra $pagoExtra)
    {
        //
    }
}
