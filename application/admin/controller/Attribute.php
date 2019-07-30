<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Attribute extends Common
{
    public function index()
    {
    	$id=Request::get('id');
    	$this->assign('id',$id);
       return $this->fetch();
    }
    public function show(){
    	$id=Request::post('id');
    	$arr=Db::query("select attr_category.id as a_id ,attr_category.name as a_name, attribute.id as at_id,attribute.name as at_name,attribute.cate_id from attr_category join attribute on attr_category.id=attribute.cate_id where cate_id='$id'");

    	// $arr=Db::table('attribute')->select();
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo $json=json_encode($res);
    	die;
    }
    public function addaction(){
    	$id=Request::post('id');
    	$name=Request::post('name');
    	$data=Request::post('');
    	 $validate = new \app\admin\validate\Attribute;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
    	$arr=Db::query("select * from attribute where name='$name' and cate_id='$id'");
    	if (empty($arr)) {
    		$data=['name'=>$name,'cate_id'=>$id];
	    	Db::table('attribute')->insert($data);
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
