<?php
/**
 * Created by PhpStorm.
 * User: wanna <hkw925@qq.com>
 * Date: 2019/12/7
 * Time: 10:49
 */

namespace Wannabing\Lib\Lib;



use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Uploader
{
    public $key = 'file'; //下标
    public $thumb = false;


    private function getStoragePath()
    {
        if (!file_exists(Storage::path('images' . DIRECTORY_SEPARATOR . date('Y-m-d')))) mkdir(Storage::path('images' . DIRECTORY_SEPARATOR . date('Y-m-d')), 0777, true);
        return Storage::path('images' . DIRECTORY_SEPARATOR . date('Y-m-d'));
    }

    public function upload()
    {
        if (request()->hasFile($this->key)) {
            $file = request()->file($this->key);
        } elseif (request()->exists($this->key)) {
            $file = request()->get($this->key);
        } else {
            throw new \Exception('请上传图片');
        }
        if (is_array($file)) {
            //多个图片
            $arr = [];
            foreach ($file as $item) {
                $arr[] = $this->uploadOne($item);
            }
            return $arr;
        } else {
            //单个图片
            return $this->uploadOne($file);
        }
    }

    private function uploadOne($file)
    {
        $img       = Image::make($file);
        $ext       = explode('/', $img->mime())[1];
        $file_name = date('YmdHis') . uniqid();
        if ($img->filesize() > 1024 * 1024 && $img->width() > 1920) {
            $img = $img->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $img->save($this->getStoragePath() . DIRECTORY_SEPARATOR . $file_name . '.' . $ext);
        return 'images' . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . $file_name . '.' . $ext;
    }
}
