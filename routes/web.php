<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\AnswerController;
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
    Route::get('/', [QueryController::class, 'index']) //質問一覧ページ
        ->name('queries.index');
    Route::resource('queries', QueryController::class); //質問CRUD

    Route::get('/my_queries', [QueryController::class, 'myQueries']) //自分の質問ページ
        ->name('queries.my_queries');
    Route::get('/answered_queries', [AnswerController::class, 'index']) //回答した質問ページ
        ->name('answers.index');

    Route::post('/queries/{query}/answers', [AnswerController::class, 'store']) //回答C
        ->name('answers.store')
        ->where('query', '[0-9]+');
    Route::name('answers.')->prefix('queries/{query}/answers/{answer}')->group(function () { //回答UD
        Route::get('/edit', [AnswerController::class, 'edit'])->name('edit');
        Route::patch('/update', [AnswerController::class, 'update'])->name('update');
        Route::delete('/destroy', [AnswerController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); //Breeze関係
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
