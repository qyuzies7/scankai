<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/scan');
});

Route::view('/scan', 'auth.login-petugas')->name('petugas.login');
Route::view('/scanqr', 'petugas.scan')->name('petugas.scan');
Route::view('/laporan', 'petugas.laporan')->name('petugas.laporan');
Route::view('/history-scan', 'petugas.history')->name('petugas.history');
Route::view('/lapor-kendala', 'petugas.kendala')->name('petugas.kendala');

Route::view('/teknisi', 'teknisi.index')->name('teknisi.index');
Route::view('/teknisi/detail-kendala', 'teknisi.detail-kendala')->name('teknisi.detail-kendala');
Route::view('/teknisi/proses', 'teknisi.proses')->name('teknisi.proses');
Route::view('/teknisi/detail-selesai', 'teknisi.detail-selesai')->name('teknisi.detail-selesai');
