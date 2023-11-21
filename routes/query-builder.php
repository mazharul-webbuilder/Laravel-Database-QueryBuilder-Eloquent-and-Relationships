<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;

Route::resource('/posts', PostsController::class);
