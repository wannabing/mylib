<?php


namespace Wannabing\Lib;


use Wannabing\Lib\Lib\FfmpegLib;
use Wannabing\Lib\Lib\PageApi;
use Wannabing\Lib\Lib\Uploader;

class Lib
{
    public function pageApi($model)
    {
        return new PageApi($model);
    }

    public function uploader()
    {
        return new Uploader();
    }

    public function ffmpegTool($file)
    {
        return new FfmpegLib($file);
    }
}