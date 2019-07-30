<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Attrdetails extends Common
{
    public function index()
    {
    	$id=Request::get('id');
    	$this->assign('id',$id);
       return $this->fetch();
    }
    public function show(){
    	$id=Request::post('id');
    	$arr=Db::query("select attribute.name as a_name,attribute.id as a_id, attr_details.id as at_id, attr_details.name as at_name, attr_details.attr_id from attr_details join attribute on attr_details.attr_id=attribute.id where attr_id='$id'");
    	// $arr=Db::query("select * from attr_details where attr_id='$id'");
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo $json=json_encode($res);
    	die;
    }
      public function addaction(){
    	$id=Request::post('id');
    	$name=Request::post('name');
    	$data=Request::post('');
    	 $validate = new \app\admin\validate\Attrdetails;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
    	$arr=Db::query("select * from attr_details where name='$name' and attr_id='$id'");
    	if (empty($arr)) {
    		$data=['name'=>$name,'attr_id'=>$id];
	    	Db::table('attr_details')->insert($data);
	    	$res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
	    	echo $json=json_encode($res);
	    	die;
    	}else{
    		$res=['code'=>'1','status'=>'error','data'=>'商品不能重复'];
	    	echo $json=json_encode($res);
	    	die;
    	}
    	
    }
    
}
