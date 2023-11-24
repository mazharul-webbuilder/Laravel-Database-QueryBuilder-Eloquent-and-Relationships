<?php

namespace App\Models; // same namespace file can use without use in top of file check Post model to trust

use Illuminate\Database\Eloquent\Builder;

trait PostScopes
{
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

}
