<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
    
});


Route::get('/storage/{filename}', function ($filename) {
    $path = storage_path('app/public/uploads/' . $filename);

    if (!File::exists($path)) {
        abort(404);

    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return response($file, 200)->header("Content-Type", $type);
});

