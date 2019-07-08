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
      $rbac = new Rbac();
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
				$rbac->cachePermission($sql[0]['id']);
			 Session::set('name',$name);
			}
		}
		echo json_encode($arr);
    }
    public function loginout(){
    	Session::clear();
        $this->redirect('login/login');
    }
  	public function md5(){
  		$ar=md5(123);
  		echo $ar;
  	}
}
