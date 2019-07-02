<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\facade\Session;
class Common extends Controller
{
    public function initialize()
    {
      $name=Session::get('name');
      
      if (empty($name)) {
       $this->redirect('login/login');
      }else{
      	$this->assign('name',$name);
      	
      }
    }
    public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
        $arr=['token'=>$token];
        echo json_encode($arr);
    }
}
