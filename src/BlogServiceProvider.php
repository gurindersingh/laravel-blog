<?php

namespace Gurinder\LaravelBlog;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__ . '/config/gblog.php' => config_path('gblog.php')], 'gblog::config');

        $this->registerMigrations();

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/views', 'gblog');

        $this->publishes([__DIR__ . '/views' => $this->app->resourcePath('views/vendor/gblog')], 'gblog::views');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/gblog.php', 'gblog');

        // Bind breadcrumbs package
        $this->app->register(
            'Superbalist\LaravelGoogleCloudStorage\GoogleCloudStorageServiceProvider'
        );

    }

    protected function registerMigrations()
    {
        if (!class_exists('CreatePostsTables')) {

            $timestamp = date('Y_m_d_His', time());

            $from = __DIR__ . "/Database/migrations/create_posts_tables.php.stub";

            $to = "{$this->app->databasePath()}/migrations/{$timestamp}_create_posts_tables.php";

            $this->publishes([$from => $to], 'gblog::migrations');
        }
    }
}