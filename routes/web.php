<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, "home"]);

Route::get('/home', [HomeController::class, "home"])->name("home");

Route::get('/login', [AuthController::class, "login"])->name("login");

Route::post('/login', [AuthController::class, "checkLogin"]);

Route::get('/logout', [AuthController::class, "logout"])->name("logout");

Route::get('/signup', [SignupController::class, "signup"])->name("signup");

Route::post('/signup', [SignupController::class, "checkSignup"]);

Route::get('/admin/signup', [AdminController::class, "signup"])->name("admin/signup");

Route::post('/admin/signup', [AdminController::class, "checkSignup"]);

Route::get('/gallery', [GalleryController::class, "gallery"])->name("gallery");

Route::get('/catering', [CateringController::class, "catering"])->name("catering");

Route::get('/booking', [BookingController::class, "booking"])->name("booking");

?>
