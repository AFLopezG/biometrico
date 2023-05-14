<?php

namespace App\Http\Controllers;

use App\Models\AfiliadoDriver;
use App\Models\Driver;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller{
    public function index()
    {
        //
        return Driver::with('afiliadoDriver')->get();
    }
    public function afiliadosRegister(Request $request){
        $afiliadoDriver=AfiliadoDriver::create([
            'afiliado_id'=>$request->afiliado_id,
            'driver_id'=>$request->driver_id,
            'fechaingreso'=>now()
        ]);
    }
    public function afiliadosDriverFechaBaja(Request $request){
        $afiliadoDriver=AfiliadoDriver::find($request->id);
        $afiliadoDriver->fechabaja=now();
        $afiliadoDriver->save();
    }

    public function create(){}

    public function listDriver(){
        return Driver::all();
    }
    public function store(StoreDriverRequest $request)
    {
        $drive=Driver::create($request->all());
        $afiliadoDriver=AfiliadoDriver::create([
            'afiliado_id'=>$request->afiliado_id,
            'driver_id'=>$drive->id,
            'fechaingreso'=>now()
        ]);
    }

    public function show(Driver $driver)
    {

    }
        //

    public function edit(Driver $driver)
    {

    }
        //

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
    public function destroy(Driver $driver)
    {
        //
        $driver->delete();
    }
}
