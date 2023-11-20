<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/migration.php';



/*==============================*/
Route::get('/', function () {
    return "Welcome";
});
