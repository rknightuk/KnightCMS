<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/media', [ApiController::class, 'media'])->name('api.media');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/uploads', [ApiController::class, 'listFiles'])->name('dashboard.uploads');

    Route::get('/new', [PostController::class, 'newPost'])->name('dashboard.new');

    Route::post('/preview', [PostController::class, 'generatePreview'])->name('preview.generate');
    Route::get('/preview', [PostController::class, 'showPreview'])->name('preview.show');

    Route::get('/files', [PostController::class, 'files'])->name('dashboard.files');

    Route::post('/new', [FileController::class, 'create'])->name('dashboard.create');
    Route::put('/file/{sha}', [FileController::class, 'update'])->name('file.update');

    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'create'])->name('media.create');
    Route::delete('/media/{id}', [MediaController::class, 'delete'])->name('media.delete');

    Route::get('/next', [MediaController::class, 'index'])->name('next.index');
    Route::post('/next', [MediaController::class, 'create'])->name('next.create');
    Route::delete('/next/{id}', [MediaController::class, 'delete'])->name('next.delete');

    Route::get('/api/link', [ApiController::class, 'link'])->name('api.link');
    Route::post('/api/file', [ApiController::class, 'uploadFile'])->name('api.file');
    Route::get('/api/tmdb', [ApiController::class, 'tmdb'])->name('api.tmdb');
    Route::get('/api/giantbomb', [ApiController::class, 'giantbomb'])->name('api.giantbomb');
    Route::get('/api/openlib', [ApiController::class, 'openlib'])->name('api.openlib');
});
