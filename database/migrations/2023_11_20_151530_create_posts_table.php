<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->from(startingValue: 1000);
            $table->unsignedBigInteger('user_id');

            /*One way*/
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete(); // when a user will delete, all post with user id will delete

            /*Another way*/
//            $table->foreignId('user_id')
//                ->constrained('users')
//                ->cascadeOnDelete();

            $table->string(column: 'title')->unique();
            $table->string(column: 'slug')->unique();
            $table->text(column: 'excerpt')->comment(comment: 'Summary of the post');
            $table->longText(column: 'description');
            $table->boolean(column: 'is_published')->default(value: false);
            $table->integer(column: 'min_to_read')->nullable(value: false);
            $table->timestamps();


            /*We can also assign unique column as below if we dont want to chaining*/
//            $table->unique('title');
//            $table->unique(['title', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
