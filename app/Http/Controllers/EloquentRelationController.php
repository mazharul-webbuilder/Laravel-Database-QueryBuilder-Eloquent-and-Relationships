<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class EloquentRelationController extends Controller
{
    public function createUserWithContact(): void
    {
       $user =  User::create([
           'name' => 'Demo User',
           'email' => 'example@demo.com',
           //...........
       ]);

       /*Insert Data using relation*/
       $user->contact()->create([
           // don't have to pass user_id, it will automatically set
           'address' => 'Fake Address',
           'city' => 'NYork',
           //..........
       ]);
    }

    public function userWithRelation(string $id): void
    {
        User::with('contact')->find($id);
    }

    public function insertDataToPivotTableForManyToManyRelation(): void
    {
        $post = Post::create([
            'title' => 'Lorem Ipsum',
            'slug' => 'lorem-ipsum'
            //...............
        ]);

        $tagIds = [1, 2, 4];

        // post has tags() belongTooMany relation, data will inset to post_tag pivot table by attach() method
        $post->tags()->attach($tagIds);
    }

    public function deleteDataToPivotTableForManyToManyRelation(): void
    {
        $post = Post::find(1020);

        $tagIds = [1, 2, 4];

        // post has tags() belongTooMany relation, data will delete from post_tag pivot table using detach() method
        $post->tags()->detach($tagIds);
    }

    public function updateSingleRowInPivotTable():void
    {
        $post = Post::find(2028);

        $previousTagId = 5;
        $newTagId = 10;

        /*This will update tag_id with new tag id of post*/
        $post->tags()->updateExistingPivot($previousTagId,[
            'tag_id' => $newTagId
        ]);


    }
}
