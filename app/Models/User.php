<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Scopes\BalaceVerifiedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new BalaceVerifiedScope());
    }

    /*Relations=========================================================================*/

    /**
     * Singular relation method name represent single record relationship with other table
     *
     * $this keyword represent current method instance, in this hasone case $this represent instance of a $user
     *
    */
    /*====================================================================================*/
    public function contact(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(related: Contact::class, foreignKey: 'user_id', localKey: 'id');
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(related: Post::class, foreignKey: 'user_id', localKey: 'id');
    }

    /**
     * For through relation naming convention is models name should use alphabetical order
     *
     * as below between company and phoneNumber alphabetically company model come first then phoneNumber model
    */
    public function companyPhoneNumber(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            related: PhoneNumber::class, // Data we want from
            through: Company::class,     // Which Through we reach the data Model
            firstKey: 'user_id',        // foreign_key of Through table
            secondKey: 'company_id',    // foreign_key of Related class
            localKey: 'id',
            secondLocalKey: 'id'
        );
    }

    /**
     *Has one of many relation
    */
    public function latestJob(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(related: Job::class)->latestOfMany();
    }

    public function oldestJob(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(related: Job::class)->oldestOfMany();
    }
    /*Relations=========================================================================*/
}
