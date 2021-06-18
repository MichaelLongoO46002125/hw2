<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\GalleryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/check/email/{email}", [CheckController::class, "checkEmail"])->name("/check/email");

Route::get("/fetch/gallery", [GalleryController::class, "fetchGallery"])->name("/fetch/gallery");

Route::get("/fetch/catering", [CateringController::class, "fetchCatering"])->name("/fetch/catering");

Route::get("/fetch/rooms", [BookingController::class, "fetchRooms"])->name("/fetch/rooms");

Route::prefix("/fetch/rooms/check_in/{check_in}/check_out/{check_out}/person_num/{person_num}/matrimonial/{matrimonial}/single/{single}")
    ->group(function () {
        Route::get("/", [BookingController::class, "fetchRooms"]);
        Route::get("/min_fee/{min_fee}", [BookingController::class, "fetchRooms"]);
        Route::get("/min_fee/{min_fee}/max_fee/{max_fee}", [BookingController::class, "fetchRooms"]);
});

?>
