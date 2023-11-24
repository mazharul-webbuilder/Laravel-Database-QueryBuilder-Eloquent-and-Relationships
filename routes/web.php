<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/migration.php';
require __DIR__.'/factories_seeders.php';
require __DIR__.'/query-builder.php';
require __DIR__.'/eloquent-orm.php';



/*==============================*/
Route::get('/', function () {
    return "Welcome";
});
