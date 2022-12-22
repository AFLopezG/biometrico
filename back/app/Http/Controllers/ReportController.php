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
        $af=DB::SELECT("SELECT a.codigo FROM pagos p inner join afiliados a on p.afiliado_id=a.id WHERE date(p.fecha)>='$fechaDesde' and date(p.fecha)<='$fechaHasta' and p.grupo_id=$grupo and p.anulado=false group by a.codigo order by cast(a.codigo as unsigned)");
        $pdf = App::make('dompdf.wrapper');
        $cadena="";

        foreach ($af as $r ) {
            if(!is_numeric($r->codigo)) $col=" style='color:black' ";
            else $col='';
           $cadena.="<div ".$col." class='textcod inline'>".$r->codigo."</div>";
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
        </style><h3 style='text-align:center;color:red;'>NOMINA DE MOVILIDADES QUE SACARON HOJA SEMANAL <br>  $g->tipo DE LA FECHA $fechaDesde A $fechaHasta</h3> <div class='wrapper '>".$cadena."</div>");
        return $pdf->stream();
    }

    public function repAsistencia($fecha,$grupo){
        $datogrupo=Grupo::find($grupo);
        $asist= DB::SELECT("SELECT a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,f.codigo
        from asistencias a inner join afiliados f on a.afiliado_id=f.id
        inner join vehiculos v on f.id=v.afiliado_id
       where date(a.fecha)='$fecha'
       and v.grupo_id=$grupo
       group by a.fecha,f.ci,f.expedido,f.nombres,f.apellidos,f.telefono,f.codigo order by cast(f.codigo as unsigned)");
        $noasis= DB::SELECT("SELECT * from afiliados a where a.id not in (select s.afiliado_id from asistencias s where date(s.fecha)='$fecha') order by cast(a.codigo as unsigned)");
        $pdf = App::make('dompdf.wrapper');
        $cadena="";
        $cadena2="";

        foreach ($asist as $r ) {
           $cadena.="<tr><td>".$r->codigo."</td> <td>".$r->nombres." ".$r->apellidos."</td></tr>";
        }

        foreach ($noasis as $r ) {
            $cadena2.="<tr><td>".$r->codigo."</td> <td>".$r->nombres." ".$r->apellidos."</td></tr>";
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
        <table><thead><tr><th>CODIGO</th><th>Nombre Afiliado</th></tr></thead><tbody>".$cadena."</tbody></table>

        <br>
        <div style='page-break-after: always;'></div>
        <h3 style='text-align:center;color:red;'>NO ASISTIERON A REUNION EN FECHA $fecha </h3>
        <table><thead><tr><th>CODIGO</th><th>Nombre Afiliado</th></tr></thead><tbody>".$cadena2."</tbody></table>

        ");
        return $pdf->stream();
    }

    public function repEconomico(Request $request){
        $gr=Grupo::find($request->grupo);
        $multa=0;
        $result=DB::SELECT("SELECT * FROM pagos p WHERE date(fecha)>='$request->ini' and date(fecha)<='$request->fin' and p.anulado=false and p.grupo_id=$request->grupo GROUP BY p.fecha,p.afiliado_id;");
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
