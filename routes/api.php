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
Auth::routes(['verify' => true]);
Route::post('/user/register',[authController::class,'register']);
Route::post('/user/login',[authController::class,'login']);
// Route::get('/user/email/verify', function () {
//      return ["message"=>"verify the email@@"];;
// })->middleware('auth:sanctum')->name('verification.notice');
// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
//     return [
//         'returnCode'=>200,
//         "message"=>"done",
//         "results"=>[]
//         ];
// })->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Route::post('/user/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return ["message"=>"new email was sent"];
// })->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::group(['middleware'=>['auth:api']],function(){
    Route::get('/user/logout',[authController::class,'logout']);
    
        Route::get('/user/profile',function(Request $request ){
            return "testooooooo";
        })->middleware(['verified']);

    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return [
        'returnCode'=>200,
        "message"=>"done",
        "results"=>[]
        ];
})->middleware(['signed'])->name('verification.verify');

Route::post('/user/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return ["message"=>"new email was sent"];
})->middleware(['throttle:6,1'])->name('verification.send');

Route::get('/user/email/verify', function (Request $request) {
     if(auth()->user()->hasVerifiedEmail()){
         return ["message"=>"true"];
     }else{
        return ["message"=>"false"];
     }
});
});

