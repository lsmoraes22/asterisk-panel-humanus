<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/liberar-acesso/{token}', [InviteController::class, 'activate'])->name('invite.activate');