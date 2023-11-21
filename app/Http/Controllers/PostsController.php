<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = DB::table('posts')->select('excerpt as summary', 'content')->get();
        $posts = DB::table('posts')->pluck('id'); // return array of all posts id's

        /*whereNot() method is used to exclude a specific value*/
        $posts = DB::table('posts')->whereNot('min_to_read', 1)->get();
        /*whereBetween to check between */
        $posts = DB::table('posts')->whereBetween('min_to_read', [1,5])->get();
        /*whereNotBetween to check between */
        $posts = DB::table('posts')->whereNotBetween('min_to_read', [1,5])->get();

        /*Aggregate methods*/
        $numberOfPost = DB::table('posts')->count();
        $sumOfMinToRead = DB::table('posts')->sum('min_to_read');
        $avg = DB::table('posts')->avg('min_to_read');
        $max = DB::table('posts')->max('min_to_read');
        $min = DB::table('posts')->min('min_to_read');

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
        /*Insert data or Ignore Insert Operation if any db rules break*/
        DB::table('posts')->insertOrIgnore([
            [

            ],
            [

            ]
        ]);
        /*Insert a row into db and return the id*/
        $insertedDataId = DB::table('posts')
            ->insertGetId([

            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        /*Upsert is used for update or insert, if given title and slug is found
        * it will perform update method, otherwise it will insert a new data row
        */
        DB::table('posts')
            ->upsert([
                [

                ],
                [

                ]
            ], ['title', 'slug']);

        /*Update with DB query*/
        DB::table('posts')
            ->where('user_id', 1010)
            ->update([

            ]);
        /*Increment*/
        DB::table('posts')
            ->where('id', 1015)
            ->increment('min_to_red', 5); // by default second parameter is 1
        /*Decrement*/
        DB::table('posts')
            ->where('id', 1015)
            ->decrement('min_to_read'); // by default value will use which is 1
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /*Delete all data from the posts table*/
        DB::table('posts')->truncate();
    }
}
