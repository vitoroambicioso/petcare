<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AdminController;
use App\Models\User;

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
    return view('welcome');
});

/**
 * rotas do usuario
 */
Route::post('/usuario', [UserController::class, 'create'])->name('user.create');
Route::post('/usuario/{id}', [UserController::class, 'getUser'])->name('user.get');
Route::put('/usuario/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::delete('/usuario/{id}', [UserController::class, 'delete'])->name('user.delete');
Route::get('/usuarios', [UserController::class, 'getAllUsers']);
Route::post('/login', [UserController::class, 'login'])->name('user.login');

/**
 * rotas da denuncia
 */
Route::post('/denuncia', [DenunciaController::class, 'create'])->name('denuncia.create');
Route::post('/denuncias/{id}', [DenunciaController::class, 'getDenuncia'])->name('denuncia.get');
Route::put('/denuncia/{id}', [DenunciaController::class, 'edit'])->name('denuncia.edit');
Route::delete('/denuncia/{id}', [DenunciaController::class, 'delete'])->name('denuncia.delete');
Route::post('/denuncias', [DenunciaController::class, 'getAllDenuncias']);

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
 * gerando token CSRF 
 */
Route::get('/token', function () {
    return csrf_token(); 
});