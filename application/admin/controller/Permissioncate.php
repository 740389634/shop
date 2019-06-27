<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
use app\admin\model\Permissioncate as PermissioncateModel;
class Permissioncate extends Common
{
    public function index()
    {
       return $this->fetch();
    }
    public function show(){
		$Permissioncate = new PermissioncateModel;
		$obj=$Permissioncate->select();

		$arr=json_decode($obj,true);

		$res=['code'=>'0','status'=>'ok','data'=>$arr];
		echo json_encode($res);
    }
    public function addAction(){
    	$rbac = new Rbac();
    	$data=Request::post('');
    	// $name=Request::post('name');
    	// $description=Request::post('description');
    	$validate = new \app\admin\validate\Permissioncate;
    	if (!$validate->check($data)) {
            $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
    	
    	$arr=$rbac->getPermissionCategory([['status', '=', $data['name']]]);
    	if (empty($arr)) {
    	$rbac->savePermissionCategory([
			'name' => $data['name'],
			'description' => $data['description'],
			'status' => 1
		]);
		$res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
		echo json_encode($res);
		die;
    	}else{
    		$res=['code'=>'2','status'=>'error','data'=>'分类名不能重复'];
			echo json_encode($res);
			die;
    	}
		
    }
    
}
