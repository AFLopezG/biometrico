<?php

namespace App\Http\Controllers;

use App\Models\vehiculo;
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
     * @param  \App\Http\Requests\StorevehiculoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorevehiculoRequest $request)
    {
        //
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
    }
}
