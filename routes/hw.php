<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| HW API Routes
|--------------------------------------------------------------------------
|
| API Routes with session
|
*/

Route::prefix("/fetch/contents")
    ->group(function () {
        Route::get("/", [ContentController::class, "fetchContents"])->name("/fetch/contents");
        Route::get("/num/{num}/offset/{offset}", [ContentController::class, "fetchContents"]);
        Route::get("/num/{num}/offset/{offset}/title/{title}", [ContentController::class, "fetchContents"]);
    }
);

Route::prefix("/fetch/favorites")
    ->group(function() {
        Route::get("/", [FavoriteController::class, "fetchFavorites"])->name("/fetch/favorites");
        Route::get("/num/{num}/offset/{offset}", [FavoriteController::class, "fetchFavorites"]);
    }
);

Route::post("/favorite/add", [FavoriteController::class, "addFavorite"])->name("/favorite/add");
Route::post("/favorite/remove", [FavoriteController::class, "removeFavorite"])->name("/favorite/remove");

Route::post("/booking/room", [BookingController::class, "bookingRoom"])->name("/fetch/booking");

?>
