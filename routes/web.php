<?php

use Illuminate\Support\Facades\Route;

//Route::view('/{app?}','welcome')->where('app','.*');
Route::get('/{app?}',[\App\Http\Controllers\HomeController::class,'index'])->where('app','.*');
