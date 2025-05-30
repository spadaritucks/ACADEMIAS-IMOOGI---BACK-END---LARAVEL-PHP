<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
    
});


Route::get('/storage', function () {
   return Artisan::call('storage:link');
});


