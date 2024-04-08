<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect()->route('books.index');})->name('main');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/sections/hidden', [\App\Http\Controllers\SectionController::class, 'hidden'])->name('sections.hidden');
Route::resource('/sections', \App\Http\Controllers\SectionController::class);

Route::get('/authors/hidden', [\App\Http\Controllers\AuthorController::class, 'hidden'])->name('authors.hidden');
Route::resource('/authors', \App\Http\Controllers\AuthorController::class);


Route::get('/books/hidden', [\App\Http\Controllers\BookController::class, 'hidden'])->name('books.hidden');
Route::get('/books/search', [\App\Http\Controllers\BookController::class, 'search'])->name('books.search');
Route::resource('/books', \App\Http\Controllers\BookController::class);

require __DIR__.'/auth.php';
