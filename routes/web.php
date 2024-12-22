<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GestaoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts/login');
});

//Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('authentication');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/gestao', [GestaoController::class, 'index'])->name('gestao')->middleware('auth');

// Table
Route::post('/get-table-status', [TableController::class, 'getStatusMesa'])->name('get-table-status')->middleware('auth');
Route::get('/get-status-mesa', [TableController::class, 'getStatusMesa'])->name('get-status-mesa');
Route::post('/atualizar-status-mesa', [TableController::class, 'atualizarStatusMesa'])->name('atualizar-status-mesa');
Route::post('/link-tables', [TableController::class, 'linkedTables'])->name('link-tables')->middleware('auth');
Route::post('/close-tables', [TableController::class, 'closeTables'])->name('close-tables')->middleware('auth');


Route::post('/buscar-cliente-telefone', [ClientController::class, 'findByCel'])->name('find-client-cel');

Route::post('/setreserva', [ReservationController::class, 'setReserva'])->name('setReserva');


Route::get('/produtos', [ProductsController::class, 'index'])->name('produtos');
Route::post('/setProduct', [ProductsController::class, 'setProduct'])->name('setProduct');
Route::post('/editProduct', [ProductsController::class, 'editProduct'])->name('editProduct');
Route::post('/deletar-produto', [ProductsController::class, 'deleteProduct'])->name('deletar-produto');
Route::post('/get-products', [ProductsController::class, 'getAllProducts'])->name('get-products')->middleware('auth');

// Route::post('/get-products', [ProductsController::class, 'getAllProducts'])->name('get-products')->middleware('auth');

Route::post('/set-order', [OrderController::class, 'setOrder'])->name('set-order')->middleware('auth');
Route::post('/get-itens-by-table', [OrderController::class, 'getProductsByTable'])->name('get-itens-by-table')->middleware('auth');
Route::post('/set-transferred', [OrderController::class, 'changeTable'])->name('set-transferred')->middleware('auth');


