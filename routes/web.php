<?php
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Models\Album;
use App\Http\Controllers\AlbumController;
use App\Models\API;


use App\Http\Controllers\TagController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('aurevoir', function () {
    return view('welcome');
});

Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
Route::post('/tags/{id}/update', [TagController::class, 'update'])->name('tags.update');
Route::delete('/tags/{id}', [TagController::class, 'destroy'])->name('tags.destroy');
Route::post('/tags/{id}/delete', [TagController::class, 'destroy'])->name('tags.destroy_post');


Route::prefix('bibliotheque')->name('bibliotheque.')->group(function () {

    Route::get('/', [AlbumController::class, 'list'])->name("list");

    Route::get('/{id}', [AlbumController::class, 'show'])->name("show")->whereNumber('id');

    Route::get("/{id}/delete", [AlbumController::class, 'delete'])->name("delete");

    Route::post('/{id}/toggle-tag/{tagId}', [AlbumController::class, 'toggleTag'])->name("toggle_tag")->whereNumber('id')->whereNumber('tagId');

    
    Route::get('/search', [AlbumController::class, 'search_all'])->name('search');

    Route::post('/add', [AlbumController::class, 'store'])->name('add');

});