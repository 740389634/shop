<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
class Goodsproduct extends Common
{
    public function index()
    {	$id=Request::get('id');
    	$this->assign('id',$id);
       return $this->fetch();
    }
    public function add(){
    	$id=Request::get('id');
    	$this->assign('id',$id);
    	return $this->fetch();
    }
    public function goodsshow(){
    	$id=Request::post('id');
    	
    	$arr=Db::query("select attr_details.`name` as at_name,attr_details.id as a_id,attribute.id as at_id,attribute.`name` as a_name,goods_attr.attr_details_id,goods_attr.attr_id from goods_attr join attribute on goods_attr.attr_id=attribute.id join attr_details on goods_attr.attr_details_id=attr_details.id where goods_attr.goods_id='$id' ");

	    		$sql=[];
		  		foreach ($arr as $key => $value) {
		  			$sql[$value['a_name']][]=$value;
		  		}
				
               
				$res=['code'=>'0','status'=>'ok','data'=>$sql];
    			echo $json=json_encode($res);
    }
    public function addaction(){
    	// $data=Request::post('');

     //  	$validate = new \app\admin\validate\Goodsproduct;
     //    if (!$validate->check($data)) {
     //       $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
     //        echo json_encode($res);
     //        die;
     //    }
    	$id=Request::post('id');
    	$box=Request::post('box');
    	$goods_attr_id=explode(',', $box);
    	array_shift($goods_attr_id);
    	$goods_attr=implode('-', $goods_attr_id);
    	$price=Request::post('price');
    	$sn_number=Request::post('sn_number');
    	if (empty($price)) {
    		$res=['code'=>'1','status'=>'error','data'=>'价格不能为空'];
    		echo $json=json_encode($res);
    		die;
    	}
    	if (empty($sn_number)) {
    		$res=['code'=>'1','status'=>'error','data'=>'库存不能为空'];
    		echo $json=json_encode($res);
    		die;
    	}
    	$data=['goods_id'=>$id,'goods_attr_id'=>$goods_attr,'price'=>$price,'sn_number'=>$sn_number];
    	Db::table('goods_prodcut')->insert($data);
    	$res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    	echo $json=json_encode($res);

    }
    public function show(){
    	$id=Request::post('id');

    	$arr=Db::query("select * from goods_prodcut where goods_id='$id'");
    	for ($i=0; $i <count($arr) ; $i++) { 
    		$new=[];
    		$goods_attr=$arr[$i]['goods_attr_id'];
    		$sql=explode('-', $goods_attr);
    		for ($j=0; $j <count($sql) ; $j++) { 
    			// var_dump($sql[$j]);
    			$row=Db::query("select * from attr_details where id='$sql[$j]'");
    			$new[]=$row[0]['name'];
    		}
    			$str=implode('-', $new);
    			$arr[$i]["key"]=$str;
    	}
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo $json=json_encode($res);
    }
    
}
