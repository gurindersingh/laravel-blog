<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::create('categories', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('slug')->unique();
        //     $table->string('label', 255);
        //     $table->text('description')->nullable();
        //     $table->integer('parent_id')->nullable();
        // });
        //
        // Schema::create('tags', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('slug')->unique();
        //     $table->string('label', 255);
        //     $table->text('description')->nullable();
        // });
        //
        // Schema::create('series', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->json('order');
        // });
        //
        // Schema::create('posts', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('slug')->unique();
        //     $table->string('title');
        //     $table->text('table_of_content')->nullable();
        //     $table->text('body')->nullable();
        //     $table->text('excerpt')->nullable();
        //     $table->json('meta')->nullable();
        //     $table->string('post_type')->default('post'); // Post or Page
        //     $table->string('post_format')->default('standard'); // standard, video, quote
        //     $table->unsignedInteger('image')->nullable();
        //     $table->dateTime('publish_at')->nullable();
        //     $table->timestamps();
        //
        //     $table->unsignedInteger('category_id')->nullable();
        //     $table->foreign('category_id')->references('id')->on('categories');
        //
        //     $table->unsignedInteger('series_id')->nullable();
        //     $table->foreign('series_id')->references('id')->on('series');
        //
        //     $table->unsignedInteger('author_id');
        //     $table->foreign('author_id')->references('id')->on('users');
        // });
        //
        // Schema::create('post_tag', function (Blueprint $table) {
        //     $table->unsignedInteger('tag_id');
        //     $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        //
        //     $table->unsignedInteger('post_id');
        //     $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        // });
        //
        // Schema::create('comments', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->text('body');
        //     $table->json('data')->nullable();
        //     $table->string('status')->default('pending'); // pending, approve, spam
        //     $table->unsignedInteger('parent_id')->nullable();
        //     $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        //     $table->unsignedInteger('user_id');
        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->unsignedInteger('post_id');
        //     $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        //     $table->timestamps();
        // });
        //
        // Schema::create('metas', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('key');
        //     $table->longText('value')->nullable();
        //     $table->unsignedInteger('metaable_id');
        //     $table->string('metaable_type');
        //     $table->unique(['key', 'metaable_id', 'metaable_type']);
        // });
        //
        // Schema::create('media', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('extension', 32);
        //     $table->boolean('public')->default(true);
        //     $table->string('mime_type', 128); // image/jpg, image/png
        //     $table->string('file_type', 128); // image , video, document
        //     $table->json('variations');
        //     $table->json('properties')->nullable();
        //     $table->string('storage_disk');
        //     $table->string('storage_bucket')->nullable();
        //     $table->unsignedInteger('uploaded_by');
        //     $table->foreign('uploaded_by')->references('id')->on('users');
        //     $table->unsignedInteger('mediaable_id')->nullable();
        //     $table->string('mediaable_type')->nullable();
        //     $table->boolean('uploaded')->default(false);
        //     $table->timestamps();
        // });
        //
        // Schema::table('posts', function (Blueprint $table) {
        //     $table->foreign('image')
        //         ->references('id')
        //         ->on('media');
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('media');
        // Schema::dropIfExists('metas');
        // Schema::dropIfExists('comments');
        // Schema::dropIfExists('post_tag');
        // Schema::dropIfExists('posts');
        // Schema::dropIfExists('series');
        // Schema::dropIfExists('tags');
        // Schema::dropIfExists('categories');
    }

}