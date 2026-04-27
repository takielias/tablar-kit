<?php

use Illuminate\Support\Facades\Route;
use TakiElias\TablarKit\Http\Controllers\FilePondController;
use TakiElias\TablarKit\Http\Controllers\JoditEditorController;

Route::group(['middleware' => config('tablar-kit.middleware', ['web', 'auth'])], function () {
    Route::post('jodit/browse', [JoditEditorController::class, 'browse'])->name('jodit.browse');
    Route::post('jodit/upload', [JoditEditorController::class, 'upload'])->name('jodit.upload');
});

Route::group(['middleware' => config('tablar-kit.middleware', ['web', 'auth'])], function () {
    Route::post(config('tablar-kit.filepond.server.url', '/filepond'), [config('tablar-kit.filepond.controller', FilePondController::class), 'process'])->name('upload-process');
    Route::delete(config('tablar-kit.filepond.server.url', '/filepond'), [config('tablar-kit.filepond.controller', FilePondController::class), 'revert'])->name('upload-revert');
});
