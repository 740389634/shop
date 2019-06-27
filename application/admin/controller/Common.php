<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use think\facade\Session;
class Common extends Controller
{
    public function initialize()
    {
      // $name=Session::set('name');
      // $id=Session::set('id');
      // if (empty($name)) {
      // 	$this->redirect('login/login');
      // }else{
      // 	$this->assign('name',$name);
      // 	$this->assign('id',$id);
      // }
    }
}
