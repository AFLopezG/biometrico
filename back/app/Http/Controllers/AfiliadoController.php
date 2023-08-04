<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Http\Requests\StoreAfiliadoRequest;
use App\Http\Requests\UpdateAfiliadoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function searchAfiliado($ci){
        return Afiliado::with('vehiculos')->where('ci',$ci)->first();
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

    public function listGrupoAf(Request $request){
        return DB::SELECT("SELECT a.id,a.ci,a.expedido,a.nombres,a.apellidos,a.codigo,a.telefono,v.placa
        FROM afiliados a INNER JOIN vehiculos v on a.id=v.afiliado_id
        inner join grupos g on v.grupo_id=g.id
        where g.id=$request->id");
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
        $afiliado->codigo=strtoupper($request->codigo);
        $afiliado->telefono=$request->telefono;
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
public function cambioBloqueado($id, Request $request){
    $afiliado=Afiliado::find($id);
    $afiliado->bloqueado=isset($request->bloqueado)?$request->bloqueado:'';
    $afiliado->save();
}
    public function cambioEstado($id){
        $afiliado=Afiliado::find($id);
        if($afiliado->estado=='ACTIVO')
            $afiliado->estado='PASIVO';
        else
            $afiliado->estado='ACTIVO';
        $afiliado->save();

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
        $afiliado->ci=strtoupper($request->ci);
        $afiliado->expedido=$request->expedido;
        $afiliado->codigo=strtoupper($request->codigo);
        $afiliado->telefono=$request->telefono;
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
