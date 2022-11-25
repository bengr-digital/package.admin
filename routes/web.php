<?php

use Bengr\Admin\Http\Controllers\BuilderController;
use Illuminate\Support\Facades\Route;

Route::post(config('admin.builder.url'), BuilderController::class)->name(config('admin.builder.name'))->middleware(config('admin.builder.middlwares'));
