<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;




Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('sign-up', [AuthController::class, 'signUp'])->name('sign-up');


Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('view-transaction', [WalletController::class, 'viewTransaction'])->name('view-transaction');
Route::post('sign-up-post', [AuthController::class, 'signUpPost'])->name('sign-up-post');
Route::post('login-post', [AuthController::class, 'loginPost'])->name('login-post');
Route::post('recharge-wallet', [WalletController::class, 'rechargeWallet'])->name('recharge-wallet');

Route::post('transfer-amount', [WalletController::class, 'transferMmount'])->name('transfer-amount');


Route::post('/verify-payment', [WalletController::class, 'verifyPayment'])->name('verify.payment');



