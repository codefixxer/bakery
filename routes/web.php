<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\CostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ShowcaseController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LaborCostController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PastryChefController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\CostCategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecordFilterController;
use App\Http\Controllers\RecipeCategoryController;
use App\Http\Controllers\ExternalSuppliesController;



Route::get('/', function () {
    return view('frontend.ingredients.create');
})->name('register');


Route::get('/', function () {
    return view('frontend.ingredients.create');
});


Route::get('/login', function () {
    return view('Auth.login');
})->name('login');



Route::resource('ingredients', IngredientController::class);
Route::resource('recipes', RecipeController::class);
Route::resource('recipe-categories', RecipeCategoryController   ::class);

Route::resource('external-supplies', ExternalSuppliesController::class);
Route::resource('break-even', ExternalSuppliesController::class);
Route::resource('showcase', ShowcaseController::class);
Route::get('showcases/{showcase}/manage', [ShowcaseController::class, 'manage'])->name('showcase.manage');




Route::resource('labor-cost', LaborCostController::class);
Route::resource('clients', ClientController::class);
 

Route::resource('cost_categories', CostCategoryController::class);

Route::resource('costs', CostController::class);
Route::get('costs-comparison', [CostController::class,'dashboard'])->name('costs.dashboard');

Route::resource('departments', DepartmentController::class);



Route::resource('news', NewsController::class);
Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

Route::resource('notifications', NotificationController::class);


Route::resource('comparison', RecordFilterController::class);


Route::resource('incomes', IncomeController::class);




Route::resource('pastry-chefs', PastryChefController::class);



Route::resource('equipment', EquipmentController::class);

Route::resource('production', ProductionController::class);




 








 





