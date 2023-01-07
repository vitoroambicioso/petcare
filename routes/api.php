<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StatusController;
use App\Models\User;
use App\Http\Controllers\TokenController;
/*use App\Http\Controllers\AuthController;*/


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * rotas do usuario
 */
Route::post('/usuario', [UserController::class, 'create'])->name('user.create');
Route::post('/usuario/{id}', [UserController::class, 'getUser'])->name('user.get');
Route::put('/usuario/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::delete('/usuario/{id}', [UserController::class, 'delete'])->name('user.delete');
Route::post('/login', [UserController::class, 'login'])->name('user.login');

/**
 * rotas do status
 */
Route::post('/status', [StatusController::class, 'create'])->name('status.create');
Route::post('/status/{id}', [StatusController::class, 'getStatus'])->name('status.get');
Route::put('/status/{id}', [StatusController::class, 'edit'])->name('status.edit');
Route::delete('/status/{id}', [StatusController::class, 'delete'])->name('status.delete');
Route::post('/denunciabyid', [DenunciaController::class, 'getDenunciaById']);

/*
 * rotas do admin
*/
Route::post('/admin', [AdminController::class, 'create'])->name('admin.create');
Route::post('/admin/{id}', [AdminController::class, 'getAdmin'])->name('admin.get');
Route::put('/admin/{id}', [AdminController::class, 'edit'])->name('admin.edit');
Route::delete('/admin/{id}', [AdminController::class, 'delete'])->name('admin.delete');
Route::post('/usuarios', [AdminController::class, 'getAllUsers']);
Route::post('/admins', [AdminController::class, 'getAllAdmins']);
Route::post('/loginadm', [AdminController::class, 'login'])->name('admin.login');

/**
 * rotas da denuncia
 */
Route::post('/denuncia', [DenunciaController::class, 'create'])->name('denuncia.create');
Route::post('/denuncias/{id}', [DenunciaController::class, 'getDenuncia'])->name('denuncia.get');
Route::put('/denuncia/{id}', [DenunciaController::class, 'edit'])->name('denuncia.edit');
Route::delete('/denuncia/{id}', [DenunciaController::class, 'delete'])->name('denuncia.delete');
Route::post('/denuncias', [DenunciaController::class, 'getAllDenuncias']);