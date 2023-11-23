<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():void
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

        /*Chunking*/
        DB::table('posts')
            ->orderBy('id')
            ->chunk(100, function ($posts){
                foreach ($posts as $post) {
                    // perform your action with $post
                }
            });

        /*lazy method use for load large number of data without consuming more  memory on server, work like chunk*/
        DB::table('posts')
            ->orderBy('id')
            ->lazy()
            ->each(function ($post){
               // perform you action with $post
            });

        /*Write RAW query using Query Builder*/
        DB::table('posts')
            ->selectRaw('count(*) as post_count')->first(); //output post_count 20

        DB::table('posts')
            ->whereRaw('created_at > NOW() - INTERVAL 1 DAY')
            ->first();

        DB::table('posts')
            ->select('user_id', DB::raw('SUM (min_to_read) as total_time'))
            ->groupBy('user_id')
            ->havingRaw('SUM(min_to_read) > 10')
            ->get();

        DB::table('posts')->orderBy('title', 'DESC')->orderBy('min_to_read')->get();

        DB::table('posts')->latest('title')->get();

        DB::table('posts')->oldest('min_to_read')->get();

        /*Search Large text*/
        DB::table('users')->whereFullText('content', 'a pieces of text from content') // content column  must be fullText type in database, otherwise it will not wok
            ->get();

        /*Limit*/
        DB::table('posts')->limit(10)->get(); // will only retrieve first 10 row

        /*Offset*/
        DB::table('posts')
            ->offset(10) // offset will skip first 10 record
            ->limit(10) // will return data from 11th to 20th
            ->get();

        /*When*/
        DB::table('posts')
            ->when(function ($query){
                return $query->where('is_published', true);
            })->get();

        /*Pagination*/
        DB::table('posts')
            ->paginate(25);

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

        /*Transaction*/
        DB::transaction(function (){
           DB::table('users')
               ->where('id', 1002)
               ->lockForUpdate() // without complete the transaction nobody can perform operation on this user until transaction complete
               ->decrement(10);


           DB::table('users')
               ->where('id', 1003)
               ->sharedLock() // same as lockForUpdate, difference is data can read only
               ->increment(10);
        });
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
