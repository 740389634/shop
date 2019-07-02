<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
use app\admin\model\Permissioncate as PermissioncateModel;
use think\facade\Session;
class Permissioncate extends Common
{
    public function index()
	{	
        $token=uniqid();
	   Session::set('token',$token);
       $this->assign('token',$token);
       return $this->fetch();
    }
    public function admin(){
    $id=Request::post('id');
	$arr=Db::table('permission_category')->where('id',$id)->select();
    $res=['code'=>'0','status'=>'ok','data'=>$arr];
	echo json_encode($res);
    
    
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
    	
    	$arr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
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
    public function p_delete(){
    	$id=Request::post('id');
        $stoken=Request::post('token');
        $session_token=Session::get('token');
        $token=uniqid();
        Session::set('token',$token);
        if ($stoken!=$session_token) {
            $res=['code'=>'0','status'=>'error','data'=>'令牌不匹配','token'=>$token];
            echo json_encode($res);
            die;
        }
    	Db::table('permission_category')->where('id',$id)->delete();
    	$res=['code'=>'1','status'=>'ok','data'=>'删除成功','token'=>$token];
		echo json_encode($res);
    }
    public function p_update(){
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
        $arr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
        if (empty($arr)) {
        	$sql=['name'=>$data['name'],'description'=>$data['description']];
			$arr=Db::table('permission_category')->where('id',$data['id'])->update($sql);
			$res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
			echo json_encode($res);
			die;
        }else{
        	if ($arr[0]['id']!=$data['id']) {
        		$res=['code'=>'2','status'=>'error','data'=>'分类名不能重复'];
		    	echo json_encode($res);
		    	die;
        	}else{
		    	$sql=['name'=>$data['name'],'description'=>$data['description']];
				$arr=Db::table('permission_category')->where('id',$data['id'])->update($sql);
				$res=['code'=>'3','status'=>'ok','data'=>'修改成功'];
				echo json_encode($res);
				die;
        	}
        	
        }
    }
    public function batchUpdate(){
    	$id=Request::post('id');
    	if (empty($id)) {
    		$res=['code'=>'0','status'=>'error','data'=>'不能为空'];
		    echo json_encode($res);
		    die;
    	}
    	$arr=explode(',', $id);
    	array_shift($arr);
    	
    	$rbac = new Rbac();
    	$rbac->delPermissionCategory($arr);
    	$res=['code'=>'1','status'=>'ok','data'=>'删除成功'];
		    echo json_encode($res);
    	
    }
    public function bothUpdate(){
        $rbac = new Rbac();
        $data=Request::post('');
           $arr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
        if (empty($arr)) {
            $sql=['name'=>$data['name']];
            $arr=Db::table('permission_category')->where('id',$data['id'])->update($sql);
            $res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            echo json_encode($res);
            die;
        }else{
            if ($arr[0]['id']!=$data['id']) {
                $res=['code'=>'2','status'=>'error','data'=>'分类名不能重复'];
                echo json_encode($res);
                die;
            }else{
                $sql=['name'=>$data['name']];
                $arr=Db::table('permission_category')->where('id',$data['id'])->update($sql);
                $res=['code'=>'3','status'=>'ok','data'=>'修改成功'];
                echo json_encode($res);
                die;
            }
            
        }
    }
}
