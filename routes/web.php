<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonsController;
use App\Http\Controllers\PolylinesController;


Route::get('/', [PointsController::class, 'index'])->name('map');

Route::get('/table', [TableController::class, 'index'])->name('table');

Route::post('/point-store', [PointsController::class, 'store'])->name('point.store');

Route::delete('/points/{id}', [PointsController::class, 'destroy'])->name('points.destroy');
Route::delete('/polylines/{id}', [PolylinesController::class, 'destroy'])->name('polylines.destroy');
Route::delete('/polygons/{id}', [PolygonsController::class, 'destroy'])->name('polygons.destroy');

Route::resource('points', PointsController::class);
Route::resource('polylines', PolylinesController::class);
Route::resource('polygons', PolygonsController::class);

