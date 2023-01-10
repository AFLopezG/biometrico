<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Http\Requests\StorevehiculoRequest;
use App\Http\Requests\UpdatevehiculoRequest;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Vehiculo::with('afiliado')->with('grupo')->get();
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
     *-
     * @param  \App\Http\Requests\StorevehiculoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorevehiculoRequest $request)
    {
        //
        $vehiculo=new Vehiculo;
        $vehiculo->placa=strtoupper($request->placa);
        $vehiculo->tipo=strtoupper($request->tipo);
        $vehiculo->modelo=$request->modelo;
        $vehiculo->marca=strtoupper($request->marca);
        $vehiculo->color=strtoupper($request->color);
        $vehiculo->codcolor=$request->codcolor;
        $vehiculo->capacidad=$request->capacidad;
        $vehiculo->afiliado_id=$request->afiliado_id;
        $vehiculo->grupo_id=$request->grupo_id;
        $vehiculo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(vehiculo $vehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function edit(vehiculo $vehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatevehiculoRequest  $request
     * @param  \App\Models\vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatevehiculoRequest $request, vehiculo $vehiculo)
    {
        //
        $vehiculo=Vehiculo::find($request->id);
        $vehiculo->placa=strtoupper($request->placa);
        $vehiculo->tipo=strtoupper($request->tipo);
        $vehiculo->modelo=$request->modelo;
        $vehiculo->marca=strtoupper($request->marca);
        $vehiculo->color=strtoupper($request->color);
        $vehiculo->codcolor=$request->codcolor;
        $vehiculo->capacidad=$request->capacidad;
        $vehiculo->afiliado_id=$request->afiliado_id;
        $vehiculo->grupo_id=$request->grupo_id;
        $vehiculo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehiculo $vehiculo)
    {
        //
        $vehiculo->delete();
    }
}
