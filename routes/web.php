<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Auth/Register', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    //acivity routes
//    Route::get('/activity', function () {
//        return Inertia::render('Activity/Activity');
//    })->name('activity');
    Route::get('/activity/create', function () {
        return Inertia::render('Activity/Create');
    })->name('activity.create');
    Route::post('/activity', [App\Http\Controllers\ActivityController::class, 'store'])->name('activity.store');

    //Reports
    Route::get('/reports', function () {
        return Inertia::render('Reports/Reports',['reports' => Auth::user()->activities()->paginate(15)]);
    })->name('reports');
    Route::post('/reports', [App\Http\Controllers\ReportsController::class, 'filter'])->name('reports.filter');
    Route::post('/reports/email', [App\Http\Controllers\ReportsController::class, 'sendEmailNotification'])->name('reports.email');
    Route::get('/reports/print', [App\Http\Controllers\ReportsController::class, 'printReport'])->name('reports.print');

});
//Unauthorized routes
Route::get('/report/{token}', [App\Http\Controllers\ReportsController::class, 'emailReport'])->name('report.notification');
