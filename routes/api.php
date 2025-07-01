<?php

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Accompagnement\AccompagnementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Faqs\FaqsController;
use App\Http\Controllers\Gallery\GalleryController;
use App\Http\Controllers\Mission\MissionController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Slide\SlideController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\Temoignage\TemoignageController;
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
    Route::get('getCountgetDashboard', 'getCountgetDashboard');
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

Route::controller(EventController::class)->group(function () {
    Route::post('/createEvent', 'createEvent');
    Route::post('/updateEvent/{id}', 'updateEvent');
    Route::get('/getEventData', 'getEventData');
    Route::get('/getEventsById/{id}', 'getEventsById');
    Route::delete('/deleteEvent/{id}', 'deleteEvent');
});

Route::controller(FaqsController::class)->group(function () {
    Route::post('/createFaqs', 'createFaqs');
    Route::put('/updateFaqs/{id}', 'updateFaqs');
    Route::get('/getFaqsData', 'getFaqsData');
    Route::delete('/deleteFaqs/{id}', 'deleteFaqs');
});

Route::controller(ServiceController::class)->group(function () {
    Route::get('getServiceData', 'getServiceData');
    Route::get('getSingleService/{id}', 'getSingleService');
    Route::post('createService', 'createService');
    Route::post('updateService/{id}', 'updateService');
    Route::delete('deleteService/{id}', 'deleteService');
});

Route::controller(ContactController::class)->group(function () {
    Route::get('/getAllContactData', 'getContact');
    Route::post('/createContact', 'storeContact');
    Route::delete('/deleteContact/{id}', 'deleteContact');
});
Route::controller(ProjectController::class)->group(function () {
    Route::post('/createProject', 'createProject');
    Route::post('/updateProject/{id}', 'updateProject');
    Route::delete('/deleteProject/{id}', 'deleteProject');
    Route::get('/getSingleProject/{id}', 'getSingleProject');
    Route::get('/getProjectData', 'getProjectData');
});

Route::controller(MissionController::class)->group(function () {
    Route::post('/createMission', 'createMission');
    Route::put('/updateMission/{id}', 'updateMission');
    Route::delete('/deleteMission/{id}', 'deleteMission');
    Route::get('/getMissionsData', 'getMissionsData');
});

Route::controller(AccompagnementController::class)->group(function () {
    Route::post('/createAccompaniement', 'createAccompaniement');
    Route::put('/updateAccompaniement/{id}', 'updateAccompaniement');
    Route::delete('/deleteAccompaniement/{id}', 'deleteAccompaniement');
    Route::get('/getAccompaniementData', 'getAccompaniementData');
});

Route::controller(TemoignageController::class)->group(function () {
    Route::get('/getAllTemoignage', 'getTemoignage');
    Route::post('/createTemoignage', 'storeTemoignage');
    Route::put('/updateTemoignage/{id}', 'updateTemoignage');
    Route::delete('/deleteTemoignage/{id}', 'deleteTemoignage');
});


