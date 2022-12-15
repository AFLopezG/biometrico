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

    public function repAsistencia(Request $request){
        return Asistencia::whereDate('fecha',$request->fecha)->with('afiliado')->get();
    }


}
