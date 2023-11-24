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
        Schema::create('post_tag', function (Blueprint $table) {
          /**
           * This pivot table is creating for achieve many-to-many relation, naming convention is use two tables names singular
           * form, as above post_tag, post from posts and tag from tags table
          */
          $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
          $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
