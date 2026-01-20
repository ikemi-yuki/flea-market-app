<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\PurchaseController;

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

Route::get('/', [ItemController::class, 'index'])->name('items.index')->middleware('profile.completed');

Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('items.detail');

Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('items.like');

Route::middleware('auth')->group(function (){
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('items.comment');

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');

    Route::post('/purchase/{item_id}', [PurchaseController::class, 'updatePayment'])->name('purchase.updatePayment');

    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit'])->name('purchase.address.edit');

    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/sell', [SellController::class, 'index'])->name('sell.index');

    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
});



