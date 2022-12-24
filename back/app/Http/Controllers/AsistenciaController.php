<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Http\Requests\StoreAsistenciaRequest;
use App\Http\Requests\UpdateAsistenciaRequest;
use DateTime;
use ElephantIO\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Asistencia::with('afiliado')->groupBy('fecha','afiliado_id')->get();
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

    public function reporte(Request $request){
        //return Asistencia::whereDate('fecha',$request->fecha)->with('afiliado')->groupBy('afiliado_id')->get();
        return DB::SELECT("SELECT a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,f.codigo
         from asistencias a inner join afiliados f on a.afiliado_id=f.id
        where date(a.fecha)='$request->fecha'
        group by a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,f.codigo  ");
    }

    public function reporteImp($fecha,$grupo){
        $result= DB::SELECT("SELECT a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,f.codigo
        from asistencias a inner join afiliados f on a.afiliado_id=f.id
        inner join vehiculos v on f.id=v.afiliado_id
       where date(a.fecha)='$fecha'
       and v.grupo_id=$grupo
       group by a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,f.codigo  ");
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAsistenciaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mifecha = new DateTime();
        $mifecha->modify('-3 second');
        $asistencia=Asistencia::where('afiliado_id',$request->afiliado_id)->orderBy('id','desc');
        if ( $asistencia->count()==0) {
            return $this->asistenciaPago($request);
        }else {
            $asistencia=$asistencia->first();
            if ($asistencia->created_at->format('Y-m-d H:i:s')<$mifecha->format('Y-m-d H:i:s')){
                return $this->asistenciaPago($request);
            }
        }
        return "Ya se registro un pago";


    }
    public function asistenciaPago($request){
        $asistencia= new Asistencia();
        $asistencia->afiliado_id=$request->afiliado_id;
        $asistencia->fecha=date('Y-m-d');
        $asistencia->hora=date('H:i:s');
        $asistencia->save();
        error_log(json_encode($asistencia));
        $url = env('URL_SOCKET');
        $client = new Client(Client::engine(Client::CLIENT_4X, $url));
        $client->initialize();
        $client->of('/');

// emit an event to the server
        $data = ["type" => "asistencia", "data" => Asistencia::where('afiliado_id',$request->afiliado_id)->with('afiliado')->orderBy('id','desc')->first()];
        $client->emit('chat message', $data);
        return $asistencia;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function show(Asistencia $asistencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Asistencia $asistencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAsistenciaRequest  $request
     * @param  \App\Models\Asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAsistenciaRequest $request, Asistencia $asistencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asistencia $asistencia)
    {
        //
    }
}
