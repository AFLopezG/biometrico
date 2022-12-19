<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Http\Requests\StoreAsistenciaRequest;
use App\Http\Requests\UpdateAsistenciaRequest;
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
        return DB::SELECT("SELECT a.fecha,
        f.ci,
        f.expedido,
        f.nombres,
        f.apellidos,
        f.codigo
         from asistencias a inner join afiliados f on a.afiliado_id=f.id
        where date(a.fecha)=$request->fecha group by a.fecha,f.ci,
        f.expedido,
        f.nombres,
        f.apellidos,
        f.codigo");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAsistenciaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asistencia= new Asistencia();
        $asistencia->afiliado_id=$request->afiliado_id;
        $asistencia->fecha=date('Y-m-d');
        $asistencia->hora=date('H:i:s');
        $asistencia->save();
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
