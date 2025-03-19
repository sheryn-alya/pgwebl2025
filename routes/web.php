<?php

use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;
use Laravel\Prompts\Table;

Route::get('/', [PointsController::class, 'index'])->name('map');

Route::get('/table', [TableController::class, 'index'])->name('table');

Route::resource('point', PointsController::class);
Route::resource('polylines', PolylineController::class);
Route::resource('polygons', PolygonController::class);
