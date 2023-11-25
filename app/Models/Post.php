<?php

namespace App\Models;

use App\Models\Scopes\PublishedWithingThirtyDaysScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes, Prunable, PostScopes; // Prunable is use for permanently delete outdated data

    protected $table = 'posts';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'string'; // Assume primary key as string instead of integer, useful when working with not integer primary key


    public $timestamps = false; // created_at and updated_at will not automatically set when $timestamps  is false

    protected $dateFormat = 'U'; // Here we can set, how the Model should behave with data and time, can set different dataFormat

    const CREATED_AT = 'date_created_at'; // change created_at column name
    const UPDATED_AT = 'date_updated_at'; // change updated_at column name

    protected $attributes = [ // set default value in a column
        "user_id" => 1,
        "is_published" => true,
        "content" => "This is content of post"
    ];

    protected $connection = 'mysql'; // determine which database should connect with this model


    protected $fillable = [
        'user_id', 'title', 'slug', 'excerpt', 'content', 'min_to_read',
    ]; // allow mass assign
    protected $guarded = [
        'is_published'
    ]; // allow Not mass assign

    /*For using slug instead of id, when route model binding*/
    // Route Should be like  this, Route::get('posts/{post:slug}, [Controller::class, 'show'])
    public function getRouteKeyName()
    {
        return 'slug';
    }


    /*Prunalbe is used for permanently delete soft deleted data or outdated data
    *
     * after write logic on method, you have to go to app/console/kernel and schedule prunable command
    */
    public function prunable():Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    /*Global Scope*/
    protected static function booted(): void
    {
        /*Global scope is use for go throw have to pass the condition for query, if global scope not pass this model data will found null*/
        static::addGlobalScope(new PublishedWithingThirtyDaysScope());
    }

    /*Local Scope*/
    public function scopePublished(Builder $builder)
    {
        return $builder->where('is_published', true);

//        Post::published()->get(); // we can use like that on controller
    }

    public function scopeWithUserData(Builder $builder)
    {
        return $builder->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.email');

//        Post::withUserData()->published->get(); // Can use like that on controller
    }

    /*Dynamic Scope*/
    public function scopePublishedByUser(Builder $builder, $userId)
    {
        return $builder->where('user_id', $userId)
            ->whereNotNull('created_at');

//        Post::publishedByUser($userId)->get(); // Can use like that on controller
    }


    /*Relations================================================================================*/
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id', ownerKey: 'id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(related: Tag::class, table: 'post_tag');
    }

    public function image(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(related: Image::class, name: 'imageable');
    }
    /*Relations================================================================================*/

}
