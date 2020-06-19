<?php


namespace Wannabing\Lib\Facades;


use Illuminate\Support\Facades\Facade;

class Lib extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wannabing\Lib\Lib::class;
    }
}