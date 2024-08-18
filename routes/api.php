<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\amenitiesClass;
use App\Http\Controllers\userQueries;
use App\Http\Controllers\unitImage;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User routes
Route::get('read', [UserController::class, 'index']);
Route::get('each_user/{id}', [UserController::class, 'show']);
Route::post('register', [UserController::class, 'create']);
Route::put('edit/{id}', [UserController::class, 'update']);
Route::delete('remove/{id}',[UserController::class, 'destroy']);
Route::get('get-profile-data', [UserController::class, 'profileDetails']); 
Route::POST('sign-in', [UserController::class, 'signin']);

// Queries
Route::get('view-queries', [userQueries::class, 'index']);
Route::delete('remove-query/{id}', [userQueries::class, 'delete']);
Route::post('add-query', [userQueries::class, 'addQuery']);

// Locaton Routes
Route::get('see-location', [LocationController::class, 'index']);
Route::get('see-location/{id}', [LocationController::class, 'each_location']);
Route::post('add-location', [LocationController::class, 'add_location']);
Route::put('edit-location/{id}', [LocationController::class, 'edit_location']);
Route::delete('delete-location/{id}', [LocationController::class, 'delete_location']);

// Units Routes
Route::get('see-units', [UnitController::class, 'index']);
Route::post('add-unit', [UnitController::class, 'add_unit']);
Route::put('edit-unit/{id}', [UnitController::class, 'updateUnitDetails']);
Route::get('see-unit/{id}', [UnitController::class, 'view_each_unit']);
Route::delete('delete-unit/{id}', [UnitController::class, 'delete_unit']);

// Amenities
Route::put('edit-amenity/{id}', [amenitiesClass::class, 'editAmenity']);
Route::get('see-amenities', [amenitiesClass::class, 'amenityIndex']);

// Image Routes
Route::get('see-images', [unitImage::class, 'indexImage']);
Route::put('edit-image/{id}', [unitImage::class, 'editImage']);
Route::delete('remove-image/{id}', [unitImage::class, 'deleteImage']);
Route::post('add-image', [unitImage::class, 'addImage']);