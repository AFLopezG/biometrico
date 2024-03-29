<?php

namespace App\Http\Controllers;

use App\Models\Cog;
use App\Http\Requests\StoreCogRequest;
use App\Http\Requests\UpdateCogRequest;
use Illuminate\Http\Request;

class CogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Cog::first();
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
     * @param  \App\Http\Requests\StoreCogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cog  $cog
     * @return \Illuminate\Http\Response
     */
    public function show(Cog $cog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cog  $cog
     * @return \Illuminate\Http\Response
     */
    public function edit(Cog $cog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCogRequest  $request
     * @param  \App\Models\Cog  $cog
     * @return \Illuminate\Http\Response
     */
    public function modcog(Request $request)
    {
        //
        $cog=Cog::first();
        $cog->state=$request->estado;
        $cog->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cog  $cog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cog $cog)
    {
        //
    }
}
