<?php
/**
 * Created by PhpStorm.
 * User: wanna <hkw925@qq.com>
 * Date: 2019/11/27
 * Time: 9:38
 */

namespace Wannabing\Lib\Lib;


class PageApi
{
    private $pageSize = 10; //每页条数
    private $model    = null; //查询模型
    private $resource = null;
    private $returned = [
            'totalPage'  => 0,
            'totalCount' => 0,
            'pageNumber' => 1,
            'list'       => []
        ]; //返回数据

    public function __construct($model)
    {
        $this->model = $model;
    }

    private function setCount()
    {
        $this->returned['totalCount'] = $this->model->count();
    }

    public function setResource($jsonResource)
    {
        $this->resource = $jsonResource;
    }

    public function paginate()
    {
        $pageNumber = request()->exists('pageNumber') ? request('pageNumber') : 1;
        $pageSize   = request()->exists('pageSize') ? request('pageSize') : $this->pageSize;
        $this->setCount();
        $offset                       = ($pageNumber - 1) * $pageSize;
        $result                       = $this->model->skip($offset)->take($pageSize)->get();
        $this->returned['totalPage']  = ceil($this->returned['totalCount'] / $pageSize);
        $this->returned['pageNumber'] = $pageNumber;
        $this->returned['list']       = $this->resource ? $this->resource::collection($result) : $result;
        return $this->returned;
    }
}