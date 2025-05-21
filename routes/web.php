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

Route::get('/points/{id}/edit', [PointsController::class, 'edit'])->name('points.edit');
Route::get('/polylines/{id}/edit', [PolylinesController::class, 'edit'])->name('polylines.edit');
Route::get('/polygons/{id}/edit', [PolygonsController::class, 'edit'])->name('polygons.edit');

Route::put('/points/{id}', [PointsController::class, 'update'])->name('points.update');
Route::put('/polylines/{id}', [PolylinesController::class, 'update'])->name('polylines.update');
Route::put('/polygons/{id}', [PolygonsController::class, 'update'])->name('polygons.update');

Route::resource('points', PointsController::class);
Route::resource('polylines', PolylinesController::class);
Route::resource('polygons', PolygonsController::class);

