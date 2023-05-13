<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Driver::with('afiliados')->get();
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
     * @param  \App\Http\Requests\StoreDriverRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDriverRequest $request)
    {
        //
        $driver= new Driver();
        $driver->ci=strtoupper($request->ci);
        $driver->celular=$request->celular;
        $driver->nombres=strtoupper($request->nombres);
        $driver->foto='';
        $driver->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDriverRequest  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $driver=  Driver::find($request);
        $driver->ci=strtoupper($request->ci);
        $driver->celular=$request->celular;
        $driver->nombres=strtoupper($request->nombres);
        $driver->save();  
    }

    public function cambiar(Request $request){
        $fecha=date('Y-m-d');
        $result=DB::SELECT("SELECT from afiliado_driver where fechabaja is not null and driver_id = $request->driver_id");
        if(sizeof($result)>0)
            { if($result[0]->afiliado_id==$request->afiliado_id)
                return '';
                else 
                DB::SELECT("UPDATE afiliado_driver set fechabaja=$fecha where driver_id = $request->driver_id");
            }
        DB::SELECT("INSERT into afiliado_driver (afiliado_id,driver_id,fechaingreso) values ($request->afiliado_id,$request->driver_id,$request->fecha)");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        //
        $driver->delete();
    }
}
