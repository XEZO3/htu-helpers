<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login',function () {
    $url = 'https://htu-helper.online'; // Replace this with the URL you want to redirect to
    return redirect()->away($url);
})->name("login");

// Route::get('/', function () {
//     return view('welcome');
// });
