<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class ReportController extends Controller
{
    public function reportList($grupo, $fechaDesde, $fechaHasta)
    {
        $af=DB::SELECT("SELECT a.codigo FROM pagos p inner join afiliados a on p.afiliado_id=a.id WHERE date(p.fecha)>='$fechaDesde' and date(p.fecha)<='$fechaHasta' and p.grupo_id=$grupo group by a.codigo");
        $pdf = App::make('dompdf.wrapper');
        $cadena="";
        foreach ($af as $r ) {
           $cadena.="<div style='width:12%'>".$r->codigo."</div>";
        }
        $pdf->loadHTML("<h1>NOMINA DE MOVILIDADES QUE SACARON HOJA SEMANAL <br> GRUPO '$grupo' DE LA FECHA $fechaDesde A $fechaHasta</h1> ".$cadena);
        return $pdf->stream();
    }

    public function repAsistencia(Request $request){
        return Asistencia::whereDate('fecha',$request->fecha)->with('afiliado')->get();
    }


}
