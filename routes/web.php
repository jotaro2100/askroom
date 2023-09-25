<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AdditionController;
use App\Models\Query;
use App\Models\Answer;
use App\Models\Addition;
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
Route::model('query', Query::class);
Route::model('answer', Answer::class);
Route::model('addition', Addition::class);

Route::middleware('auth')->group(function () {
    Route::get('/', [QueryController::class, 'index']) //質問一覧ページ
        ->name('queries.index');
    Route::get('/my_queries', [QueryController::class, 'myQueries']) //自分の質問ページ
        ->name('queries.my_queries');
    Route::get('/answered_queries', [AnswerController::class, 'index']) //回答した質問ページ
        ->name('answers.index');
    Route::get('/additions_queries', [AdditionController::class, 'index']) //補足した質問ページ
        ->name('additions.index');
    Route::get('/resolved_queries', [QueryController::class, 'resolvedQueries']) //解決済の質問ページ
        ->name('queries.resolved_queries');
    Route::get('/unresolved_queries', [QueryController::class, 'unresolvedQueries']) //未解決の質問ページ
        ->name('queries.unresolved_queries');

    Route::resource('queries', QueryController::class); //質問CRUD
    Route::get('/queries/{query}/resolve', [QueryController::class, 'toggleResolve'])->name('queries.resolve'); //解決トグル

    Route::post('/queries/{query}/answers', [AnswerController::class, 'store']) //回答C
        ->name('answers.store');
    Route::name('answers.')->prefix('queries/{query}/answers/{answer}')->group(function () { //回答UD
        Route::get('/edit', [AnswerController::class, 'edit'])->name('edit');
        Route::patch('/update', [AnswerController::class, 'update'])->name('update');
        Route::delete('/destroy', [AnswerController::class, 'destroy'])->name('destroy');
    });

    Route::post('/queries/{query}/answers/{answer}/additions', [AdditionController::class, 'store']) //補足C
        ->name('additions.store');
    Route::name('additions.')->prefix('queries/{query}/answers/{answer}/additions/{addition}')->group(function () { //補足UD
        Route::get('/edit', [AdditionController::class, 'edit'])->name('edit');
        Route::patch('/update', [AdditionController::class, 'update'])->name('update');
        Route::delete('/destroy', [AdditionController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); //Breeze関係
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
