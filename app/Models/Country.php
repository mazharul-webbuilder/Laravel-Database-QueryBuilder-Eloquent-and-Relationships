<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * Naming convention for hasManyThrough relation is,
     * use the target model name as plural form
    */
    public function posts()
    {
        return $this->hasManyThrough(
            related: Country::class, // which data we want
            through: User::class,    // which thought we reach data
            firstKey: 'country_id',  // foreign key of through model
            secondKey: 'user_id',    // foreign key of related model
            localKey: 'id',          // primary key of related model
            secondLocalKey: 'id'     // primary key of through model
        );
    }
}
