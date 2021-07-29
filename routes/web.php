<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpeadoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

/*Route::get('/empleado', function () {
    return view('empleado.index');  //accede a los elementos de views/empleado
});

Route::get('empleado/create', [EmpeadoController::class, 'create']);*/

Route::resource('empleado', EmpeadoController::class)->middleware('auth');
// quitamos registrar y olvide pass
//['register'=>false,'reset'=>false]
Auth::routes(['register'=>false,'reset'=>false]);

Route::get('/home', [EmpeadoController::class, 'index'])->name('home');

// una ves logeado redireccionar al crud
Route::group(['middleware'=>'auth'],function () {
    Route::get('/home', [EmpeadoController::class, 'index'])->name('home');
});

