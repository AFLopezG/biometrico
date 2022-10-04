<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Http\Requests\StoreAfiliadoRequest;
use App\Http\Requests\UpdateAfiliadoRequest;

class AfiliadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Afiliado::all();
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
     * @param  \App\Http\Requests\StoreAfiliadoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAfiliadoRequest $request)
    {
        //
        $afiliado= new Afiliado();
        $afiliado->ci=strtoupper($request->ci);
        $afiliado->expedido=$request->expedido;
        $afiliado->nombres=strtoupper($request->nombres);
        $afiliado->apellidos=strtoupper($request->apellidos);
        $afiliado->fechaing=$request->fechaing;
        $afiliado->dedo1='';
        $afiliado->dedo2='';
        $afiliado->dedo3='';
        $afiliado->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function show(Afiliado $afiliado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function edit(Afiliado $afiliado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAfiliadoRequest  $request
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAfiliadoRequest $request, Afiliado $afiliado)
    {
        //
        $afiliado= Afiliado::find($request->id);
        $afiliado->expedido=$request->expedido;
        $afiliado->nombres=strtoupper($request->nombres);
        $afiliado->apellidos=strtoupper($request->apellidos);
        $afiliado->fechaing=$request->fechaing;
        $afiliado->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Afiliado $afiliado)
    {
        //
        $afiliado->delete();
    }
}
