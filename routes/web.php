<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// หน้าเว็บของคุณ
Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/sell-product', [PageController::class, 'sellProduct'])->name('sell.product');
Route::get('/articles', [PageController::class, 'articles'])->name('articles');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/cancel-selling', [PageController::class, 'cancelSelling'])->name('cancel.selling');
Route::get('/sell-at-finfinphone', [PageController::class, 'sellAtFinFinPhone'])->name('sell.at.finfinphone');
Route::get('/how-to-sell', [PageController::class, 'howToSell'])->name('how.to.sell');
Route::get('/how-to-get-paid', [PageController::class, 'howToGetPaid'])->name('how.to.get.paid');

