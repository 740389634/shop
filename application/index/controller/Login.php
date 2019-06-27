<?php
namespace app\index\controller;
use think\Db;
use think\captcha\Captcha;
use think\facade\Session;
use think\Controller;
use gmars\rbac\Rbac;
class Login extends Controller
{
    public function login()
    {

       return $this->fetch();
    }
    
}
