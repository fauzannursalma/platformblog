<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-docs', function () {
    $swaggerFile = asset('openapi.json');

    return view('api-docs', compact('swaggerFile'));
});

Route::fallback(function () {
    return response()->view('404', [], 404);
});
