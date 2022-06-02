<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kelascontroller;
use App\Http\Controllers\Siswacontroller;
use App\Http\Controllers\Bukucontroller;
use App\Http\Controllers\Peminjaman_bukuController;
use App\Http\Controllers\Pengembalian_bukuController;
use App\Http\Controllers\Detail_peminjaman_bukuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("/register", [UserController::class, 'register']);
Route::post("/login", [UserController::class, 'login']);
Route::get("/book", [UserController::class, 'book']);

Route::get("/bookall", [BookController::class, 'bookAuth'])->middleware('jwt.verify');
Route::get("/user", [UserController::class, 'getAuthenticatedUser'])->middleware('jwt.verify');

// Route::get("/login_check",[UserController::class, 'getAuthenticatedUser']);

Route::group(['middleware' => ['jwt.verify']], function ()
{
    Route::group(['middleware' => ['api.superadmin']], function ()
    {
        Route::delete("/kelasmodel/{id}",[Kelascontroller::class, 'destroy']);
        Route::delete("/siswamodel/{id}",[Siswacontroller::class, 'destroy']);
        Route::delete("bukumodel/{id}",[Bukucontroller::class, 'destroy']);
        Route::delete("peminjaman_buku_model/{id}",[Peminjaman_bukuController::class, 'destroy']);
        Route::delete("pengembalian_buku_model/{id}",[Pengembalian_bukuController::class, 'destroy']);
        Route::delete("detail_peminjaman_buku_model/{id}",[Detail_peminjaman_bukuController::class, 'destroy']);
    });

    Route::group(['middleware' => ['api.admin']], function ()
    {
        Route::post("/kelasmodel", [Kelascontroller::class, 'store']);
        Route::put("/kelasmodel/{id}",[Kelascontroller::class, 'update']);

        Route::post("/siswamodel", [Siswacontroller::class, 'store']);
        Route::put("/siswamodel/{id}",[Siswacontroller::class, 'update']);
        Route::post("/siswamodel/uploadFoto/{id}", [Siswacontroller::class, 'upload_foto_siswa']);

        Route::post("/bukumodel", [Bukucontroller::class, 'store']);
        Route::post("/bukumodel/uploadCover/{id}", [Bukucontroller::class, 'upload_cover_buku']);
        Route::put("/bukumodel/{id}",[Bukucontroller::class, 'update']);

        Route::post("/peminjaman_buku_model", [Peminjaman_bukuController::class, 'store']);
        Route::put("/peminjaman_buku_model/{id}",[Peminjaman_bukuController::class, 'update']);

        // Route::post("/pengembalian_buku_model", [Pengembalian_bukuController::class, 'store']);
        Route::put("/pengembalian_buku_model/{id}",[Pengembalian_bukuController::class, 'update']);
        Route::post("/pengembalian_buku_model",[Pengembalian_bukuController::class, 'mengembalikanBuku']);


        Route::post("/detail_peminjaman_buku_model", [Detail_peminjaman_bukuController::class, 'store']);
        Route::put("/detail_peminjaman_buku_model/{id}",[Detail_peminjaman_bukuController::class, 'update']);
        Route::post("/tambah_item/{id_peminjaman_buku}", [Peminjaman_bukuController::class, 'tambahItem']);
    });


    Route::get("/login_check",[UserController::class, 'getAuthenticatedUser']);

    // Route::post("/kelasmodel", [Kelascontroller::class, 'store']);
    Route::get("/kelasmodel", [Kelascontroller::class, 'show']);
    Route::get("/kelasmodel/{id}", [Kelascontroller::class, 'detail']);
    // Route::delete("/kelasmodel/{id}",[Kelascontroller::class, 'destroy']);
    // Route::put("/kelasmodel/{id}",[Kelascontroller::class, 'update']);

    // Route::post("/siswamodel", [Siswacontroller::class, 'store']);
    Route::get("/siswamodel/{id}", [Siswacontroller::class, 'detail']);
    Route::get("/siswamodel", [Siswacontroller::class, 'show']);
    // Route::delete("/siswamodel/{id}",[Siswacontroller::class, 'destroy']);
    // Route::put("/siswamodel/{id}",[Siswacontroller::class, 'update']);
    // Route::post("/siswamodel/uploadFoto/{id}", [Siswacontroller::class, 'upload_foto_siswa']);

    // Route::post("/bukumodel", [Bukucontroller::class, 'store']);
    // Route::post("/bukumodel/uploadCover/{id}", [Bukucontroller::class, 'upload_cover_buku']);
    Route::get("/bukumodel", [Bukucontroller::class, 'show']);
    Route::get("/bukumodel/{id}", [Bukucontroller::class, 'detail']);
    // Route::delete("bukumodel/{id}",[Bukucontroller::class, 'destroy']);
    // Route::put("/bukumodel/{id}",[Bukucontroller::class, 'update']);

    // Route::post("/peminjaman_buku_model", [Peminjaman_bukuController::class, 'store']);
    Route::get("/peminjaman_buku_model", [Peminjaman_bukuController::class, 'show']);
    Route::get("/peminjaman_buku_model/{id}", [Peminjaman_bukuController::class, 'detail']);
    // Route::delete("peminjaman_buku_model/{id}",[Peminjaman_bukuController::class, 'destroy']);
    // Route::put("/peminjaman_buku_model/{id}",[Peminjaman_bukuController::class, 'update']);
    Route::post("/mengembalikan_buku", [Pengembalian_bukuController::class, 'store']);

    // Route::post("/pengembalian_buku_model", [Pengembalian_bukuController::class, 'store']);
    Route::get("/pengembalian_buku_model", [Pengembalian_bukuController::class, 'show']);
    Route::get("/pengembalian_buku_model/{id}", [Pengembalian_bukuController::class, 'detail']);
    // Route::delete("pengembalian_buku_model/{id}",[Pengembalian_bukuController::class, 'destroy']);
    // Route::put("/pengembalian_buku_model/{id}",[Pengembalian_bukuController::class, 'update']);

    // Route::post("/detail_peminjaman_buku_model", [Detail_peminjaman_bukuController::class, 'store']);
    Route::get("/detail_peminjaman_buku_model", [Detail_peminjaman_bukuController::class, 'show']);
    Route::get("/detail_peminjaman_buku_model/{id}", [Detail_peminjaman_bukuController::class, 'detail']);
    // Route::delete("detail_peminjaman_buku_model/{id}",[Detail_peminjaman_bukuController::class, 'destroy']);
    // Route::put("/detail_peminjaman_buku_model/{id}",[Detail_peminjaman_bukuController::class, 'update']);

    // Route::post("/tambah_item/{id_peminjaman_buku}", [Peminjaman_bukuController::class, 'tambahItem']);
});
