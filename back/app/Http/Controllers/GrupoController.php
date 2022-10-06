<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Http\Requests\StoreGrupoRequest;
use App\Http\Requests\UpdateGrupoRequest;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Grupo::all();
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
     * @param  \App\Http\Requests\StoreGrupoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGrupoRequest $request)
    {
        //
        $grupo=new Grupo;
        $grupo->tipo=strtoupper($request->tipo);
        $grupo->detalle=strtoupper($request->detalle);
        $grupo->rango=$request->rango;
        $grupo->sindical=$request->sindical;
        $grupo->seguro=$request->seguro;
        $grupo->deportico=$request->deportico;
        $grupo->decano=$request->decano;
        $grupo->monto=floatval($request->sindical)+floatval($request->seguro)+floatval($request->deportico)+floatval($request->decano);
        $grupo->multa=$grupo->monto;
        $grupo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function show(Grupo $grupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function edit(Grupo $grupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGrupoRequest  $request
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGrupoRequest $request, Grupo $grupo)
    {
        //
        $grupo=Grupo::find($request->id);
        $grupo->tipo=strtoupper($request->tipo);
        $grupo->detalle=strtoupper($request->detalle);
        $grupo->rango=$request->rango;
        $grupo->sindical=$request->sindical;
        $grupo->seguro=$request->seguro;
        $grupo->deportico=$request->deportico;
        $grupo->decano=$request->decano;
        $grupo->monto=floatval($request->sindical)+floatval($request->seguro)+floatval($request->deportico)+floatval($request->decano);
        $grupo->multa=$grupo->monto;
        $grupo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grupo $grupo)
    {
        //
        $grupo->delete();
    }
}
