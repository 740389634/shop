<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Attrcategory extends Common
{
    public function index()
    {
       return $this->fetch();
    }
    public function show(){

    	$arr=Db::query("select * from attr_category");
    	// $arr=Db::table('attr_category')->selete();
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo $json=json_encode($res);
    	
    }
    public function addaction(){
        $name=Request::post('name');
        $data=Request::post('');
         $validate = new \app\admin\validate\Attrcategory;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
        $arr=Db::query("select * from attr_category where name='$name'");
        if (empty($arr)) {
            $data=['name'=>$name];
            Db::table('attr_category')->insert($data);
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
