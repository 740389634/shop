<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\facade\Session;
use gmars\rbac\Rbac;
use Request;
use Redis;
use Cache;
class Common extends Controller
{
    public function initialize()
    {
       

      $name=Session::get('name');
       $arr=Cache::get($name);
            if (!$arr){
                Cache::set($name,1,3600);
            }else{
            Cache::set($name,$arr+1,3600);
            }
      if (empty($name)) {
       $this->redirect('login/login');
      }else{
      	$this->assign('name',$name);
        
      }
       $rbac = new Rbac();
      $module=Request::module();
      $class=Request::controller();
      $action=Request::action();
      $arr_class=['Permission','Permissioncate','Role','User'];
      $arr_action=['show','delete','update','addaction'];
      if (in_array($class, $arr_class)) {
          if (in_array($action, $arr_action)) {
            $str="$module/$class/$action";
            $str=strtolower($str);
            $bool=$rbac->can($str);
              if ($bool==false) {
                  header("Content-Type:application/json");
                   $res=['code'=>'10001','status'=>'error','data'=>'没有权限'];
                    echo json_encode($res);
                    die;
              }
          }

      }
     
    
      
    }

    public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
        $arr=['token'=>$token];
        echo json_encode($arr);
    }
}
