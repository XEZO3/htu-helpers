<?php

use App\Http\Controllers\authController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/user/register',[authController::class,'register']);
Route::post('/user/login',[authController::class,'login'])->name("login");
Auth::routes(['verify' => true]);
Route::get('/user/profile',function(Request $req){
    return"you are verified";
})->middleware(["auth:sanctum",'verified']);
Route::get('/user/email/verify', function () {
     return ["message"=>"verify the email@@"];;
})->middleware('auth:sanctum')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return ["message"=>"done"];
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/user/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return ["message"=>"new email was sent"];
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('/user/logout',[authController::class,'logout']);
});

