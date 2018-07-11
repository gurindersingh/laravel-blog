<?php

namespace Gurinder\LaravelBlog\Models;


use Hashids\Hashids;

trait UrlShortner
{

    public static function bootUrlShortner()
    {
        static::created(function ($model) {

            $idHasher = new Hashids();

            $model->short_url = $idHasher->encode($model->id);

            $model->save();

        });
    }

}