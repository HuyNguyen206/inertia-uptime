<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function (){
    Route::get('/dashboard/{site?}',\App\Http\Controllers\DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');
    Route::post('sites', \App\Http\Controllers\SiteStoreController::class)->name('sites.store');
    Route::patch('sites/emails/{site}', [\App\Http\Controllers\SiteStoreController::class, 'addEmailNotification'])->name('sites.emails.store');
    Route::patch('sites/emails/{site}/{email}', [\App\Http\Controllers\SiteStoreController::class, 'removeEmailNotification'])->name('sites.emails.remove');
    Route::post('endpoints', [\App\Http\Controllers\EndpointController::class, 'store'])->name('endpoint.store');
    Route::delete('endpoints/{endpoint}', [\App\Http\Controllers\EndpointController::class, 'destroy'])->name('endpoints.delete');
    Route::put('endpoints/{endpoint}', [\App\Http\Controllers\EndpointController::class, 'update'])->name('endpoints.update');
    Route::get('endpoints/{endpoint}/logs', [\App\Http\Controllers\EndpointController::class, 'getLogs'])->name('endpoints.logs.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
