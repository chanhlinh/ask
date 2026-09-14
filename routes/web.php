<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController; use App\Models\Event; use Illuminate\Support\Facades\Route;
Route::redirect('/', '/admin');
Route::get('/e/{code}',fn(string $code)=>view('app',['eventCode'=>$code,'screen'=>false]));
Route::get('/e/{code}/screen',fn(string $code)=>view('app',['eventCode'=>$code,'screen'=>true]));
Route::view('/login','app')->name('login');Route::post('/login',[AuthenticatedSessionController::class,'store'])->middleware('throttle:login');Route::post('/logout',[AuthenticatedSessionController::class,'destroy'])->middleware('auth');Route::view('/admin','app')->middleware(['auth','event.admin']);
