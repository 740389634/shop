<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Goodsattr extends Common
{
    public function index()
    {
    	$id=Request::get('id');
    	$this->assign('id',$id);
       return $this->fetch();
    }
    public function show(){
    	$id=Request::post('id');
    	$arr=Db::query("select goods_attr.id, goods.name as goods_name,attribute.name as attr_name,attr_details.name as a_name from goods_attr join goods ON goods_attr.goods_id=goods.id join attr_details on goods_attr.attr_details_id=attr_details.id join attribute on goods_attr.attr_id=attribute.id where goods_attr.goods_id='$id'");
    	// $arr=Db::query("select * from attr_category");
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo $json=json_encode($res);
    	die;
    }
    public function add(){
    	$id=Request::get('id');
    	$this->assign('id',$id);
    	return $this->fetch();
    }
    public function attrshow(){
        $id=Request::get('id');
        $sql=Db::query("select * from goods where id='$id'");
        $attr_cate_id=$sql[0]['attr_cate_id'];


    	$arr=Db::query("select * from attr_category");
    	$res=['code'=>'0','status'=>'ok','data'=>$arr,'default'=>$attr_cate_id];
    	echo $json=json_encode($res);

    }
    public function goodsshow(){
    	$id=Request::post('g_id');
    	$aid=Request::post('id');
    	if ($id=='0') {
    		$res=['code'=>'1','status'=>'error','data'=>''];
    			echo $json=json_encode($res);
    	}else{
    		$arr=Db::query("select attribute.name as a_name,attribute.id as a_id,attribute.cate_id, attr_details.id as at_id, attr_details.name as at_name, attr_details.attr_id from attr_details join attribute on attr_details.attr_id=attribute.id where attribute.cate_id='$id' ");
	    		$sql=[];
		  		foreach ($arr as $key => $value) {
		  			$sql[$value['a_name']][]=$value;
		  		}
				$row=Db::query("select * from goods_attr where goods_id='$aid'");
               
				$res=['code'=>$row,'status'=>'ok','data'=>$sql];
    			echo $json=json_encode($res);
    	}
    		
    	
    }
    public function goods(){
    	$arr=Db::query("select * from goods");
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo $json=json_encode($res);
    	die;
    	
    }
    public function addaction(){
    	$id=Request::post('id');
        $gid=Request::post('g_id');
    	$attribute_id=Request::post('attribute_id');
         if (empty($attribute_id)) {
                $date=['attr_cate_id'=>''];
                Db::table('goods')->where('id',$id)->update($date);

           }else{
                $data=['attr_cate_id'=>$gid];
                Db::table('goods')->where('id',$id)->update($data);
           }

        if (empty($attribute_id)) {
             Db::query("delete from goods_attr where goods_id=$id");
                $res=['code'=>'1','status'=>'ok','data'=>'添加成功'];
                echo $json=json_encode($res);
                die;
        }else{
             Db::query("delete from goods_attr where goods_id=$id");
            foreach ($attribute_id as $key => $value) {
               $arr=Db::query("select * from attr_details where id='$value'");

                $aid=$arr[0]['id'];
                $cate_id=$arr[0]['attr_id'];
                $data=['goods_id'=>$id,'attr_details_id'=>$aid,'attr_id'=>$cate_id];
                Db::table('goods_attr')->insert($data);
                $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
                echo $json=json_encode($res);
            }
        }
        
    }
    
}
