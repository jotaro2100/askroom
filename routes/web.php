<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [QueryController::class, 'index'])
        ->name('queries.index');
    Route::get('/my_queries', [QueryController::class, 'myQueries'])
        ->name('queries.my_queries');

    Route::get('/queries/{query}', [QueryController::class, 'show'])
        ->name('queries.show')
        ->where('query', '[0-9]+');

    Route::get('/queries/create', [QueryController::class, 'create'])
        ->name('queries.create');
    Route::post('/queries/store', [QueryController::class, 'store'])
        ->name('queries.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
