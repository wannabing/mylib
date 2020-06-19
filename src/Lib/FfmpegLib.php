<?php
/**
 * ffmpeg类
 * Created by PhpStorm.
 * User: wanna <hkw925@qq.com>
 * Date: 2019/12/26
 * Time: 11:11
 */

namespace Wannabing\Lib\Lib;


class FfmpegLib
{
    private $path;
    private $cover_path;

    function __construct($file)
    {
        if (!file_exists($file)) throw new \Exception('请选择视频文件');
        $this->path       = $file;
        $this->cover_path = storage_path('app/public');

    }

    /**
     * 获取视频封面
     * @author wanna <hkw925@qq.com>
     * @param int $time
     * @return string
     */
    public function getCover($time = 1)
    {
        $sub_path  = 'images/' . date('Y-m-d');
        $file_name = date('YmdHis') . mt_rand(1000, 9999) . '_cover.jpg';
        $save_path = $this->cover_path . '/' . $sub_path . '/' . $file_name;
        if (!file_exists($this->cover_path . '/' . $sub_path)) mkdir($this->cover_path . '/' . $sub_path, 0777, true);
        $command_str = "ffmpeg -i " . $this->path . " -y -f mjpeg -ss 3 -t " . $time . " " . $save_path;
        system($command_str);
        return $sub_path . '/' . $file_name;
    }

    /**
     * 降低视频质量
     * @author wanna <hkw925@qq.com>
     * @param string $quality
     */
    public function lowerQuality($quality = '640:360')
    {
        $command_str = 'ffmpeg -i ' . $this->path . ' -vf scale=' . $quality . ' -strict -2 -y ' . $this->path . ' -hide_banner';
        dd($command_str);
        system($command_str);
    }

    /**
     * 获取视频时长和码率
     * @author wanna <hkw925@qq.com>
     */
    public function getInfo()
    {
        $command = sprintf('/usr/bin/ffmpeg -i "%s" 2>&1', $this->path);

        ob_start();
        passthru($command);
        $info = ob_get_contents();
        ob_end_clean();

        $data = [];
        if (preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $info, $match)) {
            $data['duration'] = $match[1]; //播放时间
            $arr_duration     = explode(':', $match[1]);
            $data['seconds']  = $arr_duration[0] * 3600 + $arr_duration[1] * 60 + $arr_duration[2]; //转换播放时间为秒数
            $data['start']    = $match[2]; //开始时间
            $data['bitrate']  = $match[3]; //码率(kb)
        }
        /*if (preg_match("/Video: (.*?), (.*?), (.*?)[,\s]/", $info, $match)) {
            $data['vcodec']     = $match[1]; //视频编码格式
            $data['vformat']    = $match[2]; //视频格式
            $data['resolution'] = $match[3]; //视频分辨率
            $arr_resolution     = explode('x', $match[3]);
            $data['width']      = $arr_resolution[0];
            $data['height']     = $arr_resolution[1];
        }
        if (preg_match("/Audio: (\w*), (\d*) Hz/", $info, $match)) {
            $data['acodec']      = $match[1]; //音频编码
            $data['asamplerate'] = $match[2]; //音频采样频率
        }*/
        if (isset($data['seconds']) && isset($data['start'])) {
            $data['play_time'] = $data['seconds'] + $data['start']; //实际播放时间
        }
        $data['size'] = filesize($this->path); //文件大小
        return $data;
    }
}