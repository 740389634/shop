<?php
namespace app\admin\controller;
use think\Db;
use think\captcha\Captcha;
use think\facade\Session;
use think\Controller;
use gmars\rbac\Rbac;
use Request;
class Login extends Controller
{
    public function login()
    {
       return $this->fetch();
    }
    public function actionLogin(){
    	$name=Request::post('name');
    	$password=Request::post('password');
    	$code=Request::post('code');
    	$captcha = new Captcha();
		if( !$captcha->check($code)){
			$arr=['code'=>'0','status'=>'error','data'=>'验证码错误'];
			
		}else{
			$where=['name'=>$name,'password'=>md5($password)];
			$sql=Db::table('admin')->where($where)->select();
			if (empty($sql)) {
				$arr=['code'=>'1','status'=>'error','data'=>'用户名或者密码错误'];
			}else{
				$arr=['code'=>'2','status'=>'ok','data'=>'登录成功'];
				
			echo Session::set('name',$name);
			}
		}
		echo json_encode($arr);
    }
   
}
