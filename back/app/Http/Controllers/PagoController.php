<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Models\Vehiculo;
use DateTime;
use ElephantIO\Client;
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
        ->where('anulado',0)
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
        $request->ini=date("Y-m-d",strtotime($request->ini."- 1 days"));
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
        $datos=DB::SELECT("SELECT a.nombres,a.apellidos,v.codmovil ,a.fechaing,a.telefono,v.id,v.placa,g.tipo ".$cad."
        from afiliados a inner join pagos p on a.id=p.afiliado_id
        inner join vehiculos v on v.afiliado_id=a.id
        inner join grupos g on p.grupo_id=g.id
        where date(p.fecha)>='$request->ini' and date(p.fecha)<='$request->fin'
        group by a.nombres,a.apellidos,v.codmovil,a.fechaing,a.telefono,v.id,v.placa,g.tipo");
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
        $mifecha = new DateTime();
        $mifecha->modify('-3 second');
        $pago=Pago::where('afiliado_id',$request->afiliado_id)->orderBy('id','desc');
        if ( $pago->count()==0) {
            return $this->pagoInsert($request);
        }else {
            $pago=$pago->first();
            if ($pago->created_at->format('Y-m-d H:i:s')<$mifecha->format('Y-m-d H:i:s')){
                return $this->pagoInsert($request);
            }
        }
        return "Ya se registro un pago";


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show($grupo_id)
    {
        return Pago::where('grupo_id',$grupo_id)->with(['afiliado', 'grupo', 'vehiculo'])->groupBy('afiliado_id')->get();
    }
    public function pagoConsulta(Request $request)
    {
        return DB::select('SELECT v.codmovil,a.nombres,a.apellidos
FROM pagos p
INNER JOIN afiliados a ON p.afiliado_id=a.id
INNER JOIN vehiculos v ON p.vehiculo_id=v.id
INNER JOIN grupos g ON p.grupo_id=g.id
WHERE date(p.fecha) BETWEEN ? AND ?
AND p.grupo_id=?
GROUP BY v.codmovil,a.nombres,a.apellidos
ORDER BY v.codmovil DESC
',[$request->ini,$request->fin,$request->grupo_id]);
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

    /**
     * @param StorePagoRequest $request
     * @return Pago
     */
    public function pagoInsert(StorePagoRequest $request): Pago
    {

        $dias = array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
        $dia = $dias[date("w")];
        $vehiculo = Vehiculo::find($request->vehiculo_id);
        $pago = new Pago();
        $pago->afiliado_id = $vehiculo->afiliado_id;
        $pago->grupo_id = $vehiculo->grupo_id;
        $pago->vehiculo_id = $vehiculo->id;
        $pago->user_id = 1;
        $pago->fecha = date('Y-m-d');
        $pago->hora = date('H:i:s');
        $pago->impreso = true;
        $pago->monto = $request->monto;
        if ($dia == 'domingo' || $dia == 'jueves' || $dia == 'viernes' || $dia == 'sábado') {
            $pago->multa = true;
            $pago->monto = intval($request->monto) * 2;
        }
        $pago->save();
        error_log(json_encode($pago));
        $url = env('URL_SOCKET');
        $client = new Client(Client::engine(Client::CLIENT_4X, $url));
        $client->initialize();
        $client->of('/');

// emit an event to the server
        $data = [$pago->with('afiliado')->with('grupo')->with('vehiculo')->find($pago->id)];
        $client->emit('chat message', $data);
        return $pago;
    }

    public function resumenPago(Request $request){
        return DB::SELECT("SELECT g.tipo grupo, 
         (g.sindical * (SELECT count(*) from pagos p where p.anulado=0 and p.grupo_id=g.id and date(p.fecha)>='$request->ini' and date(p.fecha)<='$request->fin')) sindical,
         (g.decano * (SELECT count(*) from pagos p where p.anulado=0 and p.grupo_id=g.id and date(p.fecha)>='$request->ini' and date(p.fecha)<='$request->fin')) decano, 
         (g.deportico * (SELECT count(*) from pagos p where p.anulado=0 and p.grupo_id=g.id and date(p.fecha)>='$request->ini' and date(p.fecha)<='$request->fin')) deportivo, 
         (g.seguro * (SELECT count(*) from pagos p where p.anulado=0 and p.grupo_id=g.id and date(p.fecha)>='$request->ini' and date(p.fecha)<='$request->fin')) proaccidente, 
         (g.monto * (SELECT count(*) from pagos p where p.anulado=0 and p.grupo_id=g.id and date(p.fecha)>='$request->ini' and date(p.fecha)<='$request->fin')) total 
         from grupos g;");
    }
}
