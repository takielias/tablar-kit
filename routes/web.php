<?php

use Takielias\TablarKit\Http\Controllers\FilePondController;
use Takielias\TablarKit\Http\Controllers\JoditEditorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => config('tablar-kit.middleware', ['web', 'auth'])], function () {
    Route::post('jodit/browse', [JoditEditorController::class, 'browse'])->name('jodit.browse');
    Route::post('jodit/upload', [JoditEditorController::class, 'upload'])->name('jodit.upload');
});


Route::group(['middleware' => config('tablar-kit.middleware', ['web', 'auth'])], function () {
    Route::post(config('tablar-kit.filepond.server.url', '/filepond'), [config('tablar-kit.filepond.controller', FilepondController::class), 'process'])->name('filepond-process');
    Route::delete(config('tablar-kit.filepond.server.url', '/filepond'), [config('tablar-kit.filepond.controller', FilepondController::class), 'revert'])->name('filepond-revert');
});
