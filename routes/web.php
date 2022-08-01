<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\RedirectLogController;

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

Route::get('/', [ShortLinkController::class, 'index'])->name('shorten.index');

Route::get('/redirect-logs', [RedirectLogController::class, 'index'])->name('redirect_logs.index');

Route::get('{slug}', [ShortLinkController::class, 'shortenLink'])->name('shorten.link');