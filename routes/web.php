<?php

use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('calendar',[CalendarController::class, 'index'])->name('calendar.index');
Route::post('calendar',[CalendarController::class, 'store'])->name('calendar.store');
Route::patch('calendar/update/{id}',[CalendarController::class, 'update'])->name('calendar.update');
Route::delete('calendar/destroy/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
