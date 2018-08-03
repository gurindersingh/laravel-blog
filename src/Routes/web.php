<?php

Route::middleware(['web', 'auth'])
    ->prefix('/backend/')
    ->as('admin.')
    ->namespace('Gurinder\LaravelBlog\Http\Controllers')
    ->group(function () {

        Route::get('/blog', 'BlogProfileController')->name('blog');

        Route::get('/posts/create', 'PostsController@create')->name('posts.create');
        Route::resource('/taxonomies', 'TaxonomiesController');
        Route::resource('/posts', 'PostsController');
        Route::post('/posts', 'PostsController@index')->name('posts.search');

        Route::resource('/pages', 'PagesController')->only(['index', 'create', 'edit']);
        Route::post('/pages', 'PagesController@index')->name('pages.search');

        Route::resource('/tags', 'TagsController')->only(['index', 'store', 'destroy']);

        // wysiwyg images
        Route::post('/posts/{post}/images', 'PostsContentImagesController@store')->name('posts.content.images');
        Route::delete('/posts/{post}/images', 'PostsContentImagesController@destroy');
    });
