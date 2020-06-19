<?php


namespace Wannabing\Lib;


use Illuminate\Support\ServiceProvider;

class LibServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('lib',function(){
            return new Lib;
        });
    }
}