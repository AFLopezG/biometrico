<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Asistencia;
use App\Models\Grupo;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function reportList($grupo, $fechaDesde, $fechaHasta)
    {   $g=Grupo::find($grupo);
        $af=DB::SELECT("SELECT v.codmovil,p.fecha FROM pagos p inner join afiliados a on p.afiliado_id=a.id inner join vehiculos v on a.id=v.afiliado_id
        WHERE date(p.fecha)>='$fechaDesde' and date(p.fecha)<='$fechaHasta' and p.grupo_id=$grupo and p.anulado=0 group by v.codmovil,p.fecha order by cast(v.codmovil as unsigned)");
        $pdf = App::make('dompdf.wrapper');
        $cadena="";

        foreach ($af as $r ) {
            if(!is_numeric($r->codmovil)) $col=" style='color:black' ";
            else $col='';
            if(date('N',strtotime($r->fecha))==4)
                $col=" style='color:green' ";
           $cadena.="<div ".$col." class='textcod inline'>".$r->codmovil."</div>";
        }
        $pdf->loadHTML("<style>
        .textcod{
            width:12%';
            font-size:25px;
            padding:10px;
            color:blue;
            font-weight: bold;

        }
        div.wrapper {
            width: 100%; /* or whatever */
            overflow: hidden;
            white-space: nowrap;

        }

        div.inline {
            display: inline-block;
            width: 80px;
            height: 20px;

        }
        }
        </style><h3 style='text-align:center;color:red;'>NOMINA DE MOVILIDADES QUE SACARON HOJA SEMANAL <br>  $g->tipo DE LA FECHA $fechaDesde A $fechaHasta</h3>
         <div class='wrapper '>".$cadena."</div>");
        return $pdf->stream();
    }

    public function repAsistencia($fecha,$grupo){
        $datogrupo=Grupo::find($grupo);
        $asist= DB::SELECT("SELECT a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,v.codmovil
        from asistencias a inner join afiliados f on a.afiliado_id=f.id
        inner join vehiculos v on f.id=v.afiliado_id
       where date(a.fecha)='$fecha'
       and v.grupo_id=$grupo
       group by a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,v.codmovil order by cast(v.codmovil as unsigned)");
        $noasis= DB::SELECT("SELECT a.ci,a.expedido,a.nombres,a.apellidos,a.telefono,v.codmovil
        from afiliados a inner join vehiculos v on a.id=v.afiliado_id
        where a.id not in (select s.afiliado_id from asistencias s where date(s.fecha)='$fecha') and v.grupo_id=$grupo order by cast(v.codmovil as unsigned)");
        $pdf = App::make('dompdf.wrapper');
        $cadena="";
        $cadena2="";
        $cont=0;
        $cont2=0;
        foreach ($asist as $r ) {
            $cont++;
           $cadena.="<tr><td>".$cont."</td><td>".$r->codmovil."</td> <td>".$r->nombres." ".$r->apellidos."</td></tr>";
        }

        foreach ($noasis as $r ) {
            $cont2++;
            $cadena2.="<tr><td>".$cont2."</td><td>".$r->codmovil."</td> <td>".$r->nombres." ".$r->apellidos."</td></tr>";
         }
        $pdf->loadHTML("<style>
        table, th, td {
            border: 1px solid;
          }
          table {
            width: 100%;
            border-collapse: collapse;
          }
        </style>
        <h3 style='text-align:center;color:red;'>ASISTENCIA REUNION EN FECHA $fecha </h3>
        <h3 style='text-align:center;color:red;'>".$datogrupo->tipo."</h3>
        <table><thead><tr><th>No</th><th>CODIGO</th><th>Nombre Afiliado</th></tr></thead><tbody>".$cadena."</tbody></table>
          <div><b>TOTAL ASISTENCIA: </b>".$cont."</div>
        <br>
        <div style='page-break-after: always;'></div>
        <h3 style='text-align:center;color:red;'>NO ASISTIERON A REUNION EN FECHA $fecha </h3>
        <table><thead><tr><th>No</th><th>CODIGO</th><th>Nombre Afiliado</th></tr></thead><tbody>".$cadena2."</tbody></table>
        <div><b>TOTAL AUSENTES: </b>".$cont2."</div>

        ");
        return $pdf->stream();
    }

    public function repEconomico(Request $request){
        $gr=Grupo::find($request->grupo);
        $multa=0;
        $result=DB::SELECT("SELECT * FROM pagos p
        WHERE date(fecha)>='$request->ini' and date(fecha)<='$request->fin' and p.anulado=0 and p.grupo_id=$request->grupo GROUP BY p.fecha,p.afiliado_id;");
        if(sizeof($result)==0){
            $tsindical=0;
            $tseguro=0;
            $tdeportico=0;
            $tdecano=0;
            $multa=0;
        }
        else{
            $tsindical=$gr->sindical * sizeof($result);
            $tseguro=$gr->seguro * sizeof($result);
            $tdeportico=$gr->deportico * sizeof($result);
            $tdecano=$gr->decano * sizeof($result);
            foreach ($result as $j) {
                # code...
                $multa+=$j->multa;
            }
        }
        return ['sindical'=>$tsindical,'seguro'=>$tseguro,'deportivo'=>$tdeportico,'decano'=>$tdecano,'multa'=>$multa];

    }


}
