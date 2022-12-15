<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class ReportController extends Controller
{
    public function reportList($grupo, $fechaDesde, $fechaHasta)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML("<h1>Test</h1> $grupo $fechaDesde $fechaHasta");
        return $pdf->stream();
    }
}
