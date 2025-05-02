<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecordFilterController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeCategoryController;
use App\Http\Controllers\ExternalSuppliesController;
use App\Http\Controllers\ReturnedGoodController;
use App\Http\Controllers\ShowcaseController;
use App\Http\Controllers\LaborCostController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CostCategoryController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\PastryChefController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\RolesController;

// Public landing

     Route::get('/',    [AuthController::class, 'showLoginForm'])->name('login');

// Authentication
Route::get('login',    [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login',   [AuthController::class, 'login'])->name('login.submit');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register',[AuthController::class, 'register'])->name('register.submit');
Route::post('logout',  [AuthController::class, 'logout'])->name('logout');

// All routes below require authentication
Route::middleware('auth')->group(function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // User Management (requires "manage-users" permission)
    Route::middleware('can:manage-users')->group(function(){
        Route::resource('users',       UserController::class);
      
        Route::resource('permissions', PermissionController::class);
    });

    // Ingredients (requires "ingredients" permission)
    Route::resource('ingredients', IngredientController::class)
         ->middleware('can:ingredients');

    // Sale Comparison
    Route::get('comparison', [RecordFilterController::class, 'index'])
         ->name('comparison.index')
         ->middleware('can:sale comparison');
    Route::post('records/add-income',[RecordFilterController::class,'addFiltered'])
         ->name('income.addFiltered')
         ->middleware('can:sale comparison');

    // Recipes
    Route::resource('recipes', RecipeController::class)
         ->middleware('can:recipe');

    // Recipe Categories
    Route::resource('recipe-categories', RecipeCategoryController::class)
         ->middleware('can:recipe categories');

    // External Supplies & Templates
    Route::resource('external-supplies', ExternalSuppliesController::class)
         ->middleware('can:external supplies');
    Route::get('external-supplies/template/{id}',
         [ExternalSuppliesController::class,'getTemplate'])
         ->name('external-supplies.template')
         ->middleware('can:external supplies');

    // Returned Goods
    Route::resource('returned-goods', ReturnedGoodController::class)
         ->middleware('can:returned goods');

    // Daily Showcase & Templates
    Route::resource('showcase', ShowcaseController::class)
         ->middleware('can:showcase');
    Route::get('showcase/recipe-sales', [ShowcaseController::class,'recipeSales'])
         ->name('showcase.recipeSales')
         ->middleware('can:showcase');
    Route::get('showcase/{showcase}/manage', [ShowcaseController::class,'manage'])
         ->name('showcase.manage')
         ->middleware('can:showcase');
    Route::get('showcase/template/{id}', [ShowcaseController::class,'getTemplate'])
         ->name('showcase.template')
         ->middleware('can:showcase');

    // Labor Cost
    Route::resource('labor-cost', LaborCostController::class)
         ->middleware('can:labor cost');

    // Clients
    Route::resource('clients', ClientController::class)
         ->middleware('can:clients');

    // Cost Categories
    Route::resource('cost_categories', CostCategoryController::class)
         ->middleware('can:cost categories');

    // Costs & Comparison Dashboard
    Route::resource('costs', CostController::class)
         ->middleware('can:costs');
    Route::get('costs-comparison', [CostController::class,'dashboard'])
         ->name('costs.dashboard')
         ->middleware('can:cost comparison');

    // Departments
    Route::resource('departments', DepartmentController::class)
         ->middleware('can:departments');

    // News & Notifications
    Route::resource('news', NewsController::class)
         ->middleware('can:news');
    Route::post('notifications/{id}/mark-as-read',
         [NotificationController::class,'markAsRead'])
         ->name('notifications.markAsRead')
         ->middleware('can:news');
    Route::resource('notifications', NotificationController::class)
         ->only(['index','show'])
         ->middleware('can:news');

    // Standalone Income resource
    Route::resource('incomes', IncomeController::class)
         ->middleware('can:income');

    // Pastry Chefs
    Route::resource('pastry-chefs', PastryChefController::class)
         ->middleware('can:pastry chefs');

    // Equipment
    Route::resource('equipment', EquipmentController::class)
         ->middleware('can:equipment');

    // Production Entries & Templates
    Route::resource('production', ProductionController::class)
         ->middleware('can:production');
    Route::get('production/template/{id}',
         [ProductionController::class,'getTemplate'])
         ->name('production.template')
         ->middleware('can:production');
});

Route::resource('roles', RolesController::class);
