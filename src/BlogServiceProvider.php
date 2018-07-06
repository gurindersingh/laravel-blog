<?php

namespace Gurinder\LaravelBlog;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__ . '/config/gblog.php' => config_path('gblog.php')], 'gblog::config');

        if (!class_exists('CreateBlogTables')) {

            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . "/Database/migrations/create_blog_tables.php.stub" => $this->app->databasePath() . "/migrations/{$timestamp}_create_blog_tables.php",
            ], 'gblog::migrations');
        }

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/views', 'gblog');

        $this->publishes([__DIR__ . '/views' => $this->app->resourcePath('views/vendor/gblog')], 'gblog::views');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/gblog.php', 'gblog');

        $this->app->register(
            'Superbalist\LaravelGoogleCloudStorage\GoogleCloudStorageServiceProvider'
        );

    }
}