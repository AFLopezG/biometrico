<?php

use ElephantIO\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', [App\Http\Controllers\UserController::class, 'login']);
Route::get('reportlist/{grupo}/{fechaDesde}/{fechasHasta}', [\App\Http\Controllers\ReportController::class, 'reportList']);
Route::get('repAsistencia/{fecha}/{grupo}', [\App\Http\Controllers\ReportController::class, 'repAsistencia']);
Route::resource('pago', \App\Http\Controllers\PagoController::class);
Route::resource('asistencia', \App\Http\Controllers\AsistenciaController::class);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('me', [App\Http\Controllers\UserController::class, 'me']);
    Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);
    Route::apiResource('user', App\Http\Controllers\UserController::class);
    Route::put('/updatePassword/{user}',[\App\Http\Controllers\UserController::class,'updatePassword']);
    Route::put('/updatepermisos/{user}',[\App\Http\Controllers\UserController::class,'updatepermisos']);
    Route::resource('/permiso',\App\Http\Controllers\PermisoController::class);
    Route::resource('afiliado', \App\Http\Controllers\AfiliadoController::class);
    Route::resource('grupo', \App\Http\Controllers\GrupoController::class);
    Route::resource('vehiculo', \App\Http\Controllers\VehiculoController::class);

    Route::post('anularPago', [\App\Http\Controllers\PagoController::class,'anularPago']);
    Route::post('consultapago', [\App\Http\Controllers\PagoController::class,'consultapago']);
    Route::post('datoimp', [\App\Http\Controllers\PagoController::class,'datoimp']);
    Route::post('listGrupoAf', [\App\Http\Controllers\AfiliadoController::class,'listGrupoAf']);
    Route::post('reporte', [\App\Http\Controllers\AsistenciaController::class,'reporte']);
    Route::post('pagoConsulta', [\App\Http\Controllers\PagoController::class,'pagoConsulta']);
});
Route::get('test', function () {
    $pago = \App\Models\Pago::find(1);
    error_log(json_encode($pago));
    $url = "http://localhost:3005";
    $client = new Client(Client::engine(Client::CLIENT_4X, $url));
    $client->initialize();
    $client->of('/');

// emit an event to the server
    $data = [$pago->with('afiliado')->with('grupo')->with('vehiculo')->find($pago->id)];
    $client->emit('chat message', $data);
    return $pago;
});
