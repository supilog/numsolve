<?php

use App\Http\Controllers\NumSolvesController;
use Illuminate\Support\Facades\Route;

// 認証を問わないアクセス
Route::group(['middleware' => ['web']], function () {
    // トップページ
    Route::get('/', [NumSolvesController::class, 'index'])->name('index');
    // 追加
    Route::get('/create', [NumSolvesController::class, 'create'])->name('create');
    // 追加処理
    Route::post('/store', [NumSolvesController::class, 'store'])->name('store');
    // 問題表示
    Route::get('/q/{key}', [NumSolvesController::class, 'show'])->name('show');
    // 解く処理
    Route::post('/solve', [NumSolvesController::class, 'solve'])->name('solve');
});