<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Index extends Common
{
    public function index()
    {
       return $this->fetch();
    }
    
}
