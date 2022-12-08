<?php

use Bengr\Admin\Http\Controllers\BuilderController;
use Bengr\Admin\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

Route::get(config('admin.builder.url'), BuilderController::class)->name(config('admin.builder.name'))->middleware(config('admin.builder.middlewares'));
Route::get(config('admin.resources.url'), [ResourceController::class, 'get'])->name(config('admin.resources.name'))->middleware(config('admin.resources.middlewares'));
