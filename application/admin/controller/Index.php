<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
class Index extends Controller
{
    public function index()
    {
       return $this->fetch();
    }
}
