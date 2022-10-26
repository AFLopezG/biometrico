<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Pago::with(['afiliado', 'grupo', 'vehiculo', 'user'])->whereFecha(date('Y-m-d'))->orderBy('id', 'desc')->get();
    }

    public function consultapago(Request $request){

        return Pago::with(['afiliado', 'grupo', 'vehiculo', 'user'])
        ->whereDate('fecha','>=',$request->ini)
        ->whereDate('fecha','<=',$request->fin)
        ->orderBy('id', 'desc')->get();
        /*
        SELECT DATE_ADD('2022-10-01', INTERVAL row DAY) AS fecha1,
        DATE_ADD(DATE_ADD('2022-10-01', INTERVAL row DAY),INTERVAL 6 DAY) AS fecha2
        FROM ( SELECT @row := @row+1 AS row FROM INFORMATION_SCHEMA.COLUMNS, (SELECT @row := 0) t ) T
         WHERE DATE_ADD('2022-10-01', INTERVAL row DAY) BETWEEN '2022-10-01' AND '2022-10-31'
         AND DAYOFWEEK(DATE_ADD('2022-10-01', INTERVAL row DAY)) = 2; */
    }

    public function datoimp(Request $request){
        $respago=DB::SELECT("
        SELECT DATE_ADD('$request->ini', INTERVAL row DAY) AS fecha1,
        DATE_ADD(DATE_ADD('$request->ini', INTERVAL row DAY),INTERVAL 6 DAY) AS fecha2
        FROM ( SELECT @row := @row+1 AS row FROM INFORMATION_SCHEMA.COLUMNS, (SELECT @row := 0) t ) T
         WHERE DATE_ADD('$request->ini', INTERVAL row DAY) BETWEEN '$request->ini' AND '$request->fin'
         AND DAYOFWEEK(DATE_ADD('$request->ini', INTERVAL row DAY)) = 2; ");
        $cad='';
         foreach($respago as $r){
            $cad.=" ,(SELECT case when count(*)>0
            then 'x'
            else ''
          end
          from pagos p2 where date(p2.fecha)>='$r->fecha1'
          and date(p2.fecha)<='$r->fecha2' and p2.anulado=false
          and p2.vehiculo_id=v.id ) as '$r->fecha1'";
        }
        $datos=DB::SELECT("SELECT a.nombres,a.apellidos,a.codigo as telefono,a.fechaing,v.id,v.placa ".$cad."
        from afiliados a inner join pagos p on a.id=p.afiliado_id
        inner join vehiculos v on p.vehiculo_id=v.id
        where date(p.fecha)>='$request->ini' and date(p.fecha)<='$request->fin'
        group by a.nombres,a.apellidos,a.codigo,a.fechaing,v.id,v.placa");
        return $datos;
    }

    public function anularPago(Request $request){
        //return $request;
        $pago=Pago::find($request->pago);
        $pago->anulado=true;
        $pago->motivo=$request->motivo;
        $pago->save();
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
     * @param  \App\Http\Requests\StorePagoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePagoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePagoRequest  $request
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePagoRequest $request, Pago $pago)
    {
        $request['hora']=date('H:i:s');
        $pago->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
