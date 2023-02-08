<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RecipeController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\MetricController;
use App\Http\Controllers\API\NutritionController;
use App\Http\Controllers\API\SpecialEventsController;
use App\Http\Controllers\API\ClientFolderController;
use App\Http\Controllers\API\HomeworkFolderController;
use Illuminate\Support\Facades\Auth;

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

Route::middleware('auth:sanctum')->group(function () {
    // return $request->user();


    //The Routes for the show methods

    Route::get('allProducts', [ProductController::class, 'index']);
    Route::get('allHomework/{email}', [HomeworkFolderController::class, 'index']);
    Route::get('allRecipes', [RecipeController::class, 'index']);

    //Nutrition Plan Show Based on Time of Day
    Route::get('breakfastNutrition', [NutritionController::class, 'breakfastIndex']);
    Route::get('lunchNutrition', [NutritionController::class, 'lunchIndex']);
    Route::get('dinnerNutrition', [NutritionController::class, 'dinnerIndex']);


    Route::get('products/{id}', [ProductController::class, 'show'], function (Request $id) {
        return 'Products ' . $id;
    });


    Route::get('Recipe/{id}', [RecipeController::class, 'show'], function (Request $id) {
        return 'Recipe ' . $id;
    });

    Route::get('event/{id}', [SpecialEventsController::class, 'show'], function (Request $id) {
        return 'Events ' . $id;
    });

    Route::get('nutritionPlan/{id}', [NutritionController::class, 'show'], function (Request $id) {
        return 'Events ' . $id;
    });


    //The routes for the Admin Functionality        

    Route::post('/addProducts', [ProductController::class, 'store']);
    Route::put('/updateProducts/{id}', [ProductController::class, 'update'], function (Request $id) {
        return 'Products ' . $id;
    });
    Route::delete('/deleteProducts/{id}', [ProductController::class, 'destroy']);

    Route::post('/addRecipe', [RecipeController::class, 'store']);
    Route::put('/updateRecipe/{id}', [RecipeController::class, 'update'], function (Request $id) {
        return 'Recipe ' . $id;
    });
    Route::delete('/deleteRecipe/{id}', [RecipeController::class, 'destroy']);



    Route::post('/addEvent', [SpecialEventsController::class, 'store']);
    Route::put('/updateEvent/{id}', [SpecialEventsController::class, 'update'], function (Request $id) {
        return 'Recipe ' . $id;
    });
    Route::delete('/deleteEvent/{id}', [SpecialEventsController::class, 'destroy']);


    //Nutrition

    Route::post('/nutritionPlan', [NutritionController::class, 'store']);
    Route::put('/updateNutritionPlan/{id}', [NutritionController::class, 'update'], function (Request $id) {
        return 'Recipe ' . $id;
    });
    Route::delete('/deleteNutritionPlan/{id}', [NutritionController::class, 'destroy']);


    //Metrics
    Route::put('/addMetrics/{email}', [MetricController::class, 'update']);
    Route::get('metrics/{email}', [MetricController::class, 'show']);


    ////ADMIN FUNCTION
    Route::post('/addAdmins', [AdminController::class, 'register']);

    Route::get('allUsers', [ClientFolderController::class, 'index']);


    Route::get('user/{email}', [ClientFolderController::class, 'show'], function (Request $email) {
        return 'User ' . $email;
    });


    Route::post('/logout', [RegisterController::class, 'logout']);




    Route::post('/addHomework/{email}', [HomeworkFolderController::class, 'store']);
    Route::put('/updateHomework/{id}', [HomeworkFolderController::class, 'update']);


    //Strong and Weak Points
    Route::put('/addProperties/{email}', [ClientFolderController::class, 'clientProperties']);
    Route::get('properties/{email}', [ClientFolderController::class, 'clientPropertiesShow']);
});





Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login'])->name('login');









Route::post('forget-password', [RegisterController::class, 'forgotPassword']);
