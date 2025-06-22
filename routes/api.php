<?php

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Gallery\GalleryController;
use App\Http\Controllers\Slide\SlideController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::post('/createUSer', 'storeUser');
    Route::put('/updateUser/{id}', 'updateUser');
    Route::delete('/deleteUser/{id}', 'deleteUser');
    Route::get('/getAllUser', 'getUsers');
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
    });
});

Route::controller(SlideController::class)->group(function () {
    Route::post('/createSlide', 'storeSlide');
    Route::post('/updateSlide/{id}', 'updateSlide');
    Route::delete('/deleteSlide/{id}', 'deleteSlide');
    Route::get('/getAllSlideData', 'getAllSlideData');
    Route::get('/getOneSilde', 'getOneSilde');
});

Route::controller(AboutController::class)->group(function () {
    Route::post('/createAbout', 'createAbout');
    Route::post('/updateAbout/{id}', 'updateAbout');
    Route::get('/getAllAboutData', 'getAllAboutData');
    Route::delete('/deleteAbout/{id}', 'deleteAbout');
});

Route::controller(TeamController::class)->group(function () {
    Route::get('/getAllTeamData', 'getAllTeamData');
    Route::post('/createTeam', 'storeTeam');
    Route::post('/updateTeam/{id}', 'updateTeam');
    Route::delete('/deleteTeam/{id}', 'deleteTeam');
});

Route::controller(GalleryController::class)->group(function () {
    Route::get('/getAllGalleyData', 'getAllGalleyData');
    Route::get('/getSixImagesGallery', 'getSixGallery');
    Route::post('/createGallery', 'storeGallery');
    Route::post('/updateGallery/{id}', 'updateGallery');
    Route::delete('/deleteGallery/{id}', 'deleteGallery');
    Route::get('/getSingleGallery/{id}', 'getSingleGallery');
});
