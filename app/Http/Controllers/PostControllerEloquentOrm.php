<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostControllerEloquentOrm extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*For Yajra Datatable*/
        Post::query(); // This will optimize memory use and fast  rersponse

        Post::all();
        Post::all()->count();

        /*First check data is existed by first array, if exist return the data otherwise create a new record by passing 2nd array*/
        Post::firstOrCreate([
            'title' => 'title is match'
        ],[
            'title' => 'No data found, create new title',
            'slug' => 'no-data........',
            //..........
        ]);

        Post::withoutGlobalScopes()->get();


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = new Post();
        $post->fill([
            'title' => 'Post number 2',
            //.......
        ]);
        $post->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Post::firstWhere('slub', 'replaceYourSlugValue'); // will return first match slug
        Post::findOrFail($id); // if not found throw an exception

        $post = Post::find('100');
        $post->title = 'Change Title';

        /*isDarty*/
        if ($post->isDarty('title')){ // isDarty return true or false, after saving a data isDarty will not abale to check data is darty or not
            info('Post title change');
        } else {
            info('Post not change');
        }

        /*isClean allow array also as param*/
        $post = Post::find(1002);

        if ($post->isClean('title')) { // isClean will check is any attribute of column has been change after retrieve, same as isDarty after save the object will no able to check is Clean or not
            $flag =  'clean';
        } else {
            $flag =  'modified';
        }

        /*wasChanged*/
        if ($post->wasChanged('title')) { // will not work after save the object

        } else {

        }

        /*Replicate Model*/
        $post = Post::create([
            'title' => 'title for',
            'slug' => 'slug'
            //........
        ]);

        $post->replicate()->fill([
            // here pass the unique column data for database
            'slug' => 'replicated slug'
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /*Will return number of row affected by this action*/
        Post::where('id', $id)->update([
            'title' => 'updated title',
            'slug' => 'slug updated',
        ]);


        Post::updateOrCreate([
            'id' => 1000
        ], [
            'title' => 'Update or create',
            'slug' => 'slug',
            // .....
        ]);

        Post::upsert([],[]);


    }
    /**
     * Soft-delete a resource
    */
    public function softDelete(string $id)
    {
        $post = Post::find($id);

        $post->delete(); // it will soft delete the post
    }
    /**
     * Soft-delete a resource
    */
    public function restore(string $id)
    {
        $post = Post::find($id);

        $post->restore();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Post::truncate();

        Post::where('id', 1000)->delete();

        Post::destroy(['id1', 'id2', '........']);

        Post::where('id', 1000)->forceDelete();

    }
}
