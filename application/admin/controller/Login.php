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
			$where=['user_name'=>$name,'password'=>$password];
			$sql=Db::table('user')->where($where)->select();
			if (empty($sql)) {
				$arr=['code'=>'1','status'=>'error','data'=>'用户名或者密码错误'];
			}else{
				$arr=['code'=>'2','status'=>'ok','data'=>'登录成功'];
				
			 Session::set('name',$name);
			}
		}
		echo json_encode($arr);
    }
    public function loginout(){
    	Session::delete('name');
        $this->redirect('login/login');
    }
  	
}
