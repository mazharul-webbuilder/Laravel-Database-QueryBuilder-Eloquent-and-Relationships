<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/migration.php';
require __DIR__.'/factories_seeders.php';



/*==============================*/
Route::get('/', function () {
    return "Welcome";
});
