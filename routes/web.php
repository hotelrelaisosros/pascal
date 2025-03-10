<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\StudentController;

use App\Http\Controllers\SquareController;
use App\Http\Controllers\SubjectsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Jobs\SendWelcomeEmail;

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
    return view('auth.login');
});

Route::get('/dashboard', function () {
    App\Jobs\SendWelcomeEmail::dispatch();
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');



Route::get('/students/{id?}', [StudentController::class, 'index'])->name('students.index');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');


Route::post('/students/addSubject', [StudentController::class, 'add_subject'])->name('students.addsubject');

Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');


Route::get('/subject/{id?}', [SubjectsController::class, 'index'])->name('subject.index');
Route::post('/subject', [SubjectsController::class, 'store'])->name('subject.store');
Route::get('/subject/create', [SubjectsController::class, 'create'])->name('subject.create');
Route::post('/subject/{id}', [SubjectsController::class, 'update'])->name('subject.update');
Route::delete('/addSubject/{id}', [SubjectsController::class, 'destroy'])->name('subject.destroy');


// Route::get('/students/edit/{id}', [StudentController::class, 'edit'])->name('students.edit');
// Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.auth');
Route::get('/auth/google/call-back', [GoogleController::class, 'callbackGoogle'])->name('google.callback');












require __DIR__ . '/auth.php';
