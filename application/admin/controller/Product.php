<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
use Redis;
use Cache;
class Product extends Common
{
    public function index()
    {	
    	$arr=Db::query("select * from brand");
    	$this->assign('arr',$arr);
    	
       	return $this->fetch();
    }
    public function show(){
           $redis = new Redis;
           $redis->connect('127.0.0.1',6379);
    	 $search=Request::post('search');
           $haxi=$redis->ZREVRANGE ('rank:2018',0,4);


       if (empty($search)) {
          $arr=Db::query("select g.id,g.goods_id, g.name,brand.brand_name,brand.id as brand_id,goods_cate.brand_name as goods_brand_name from goods as g join brand ON g.brand_id=brand.id JOIN goods_cate on g.goods_id=goods_cate.id limit 0,300");
       }else{
          
            
            $redis->hSetnx("key1","$search",0);
            $redis->hIncrBy("key1","$search",1);
            $count=$redis->hGet("key1","$search");
            $redis->ZINCRBY ('rank:2018',$count,$search);
           

             // $rw=$redis->zAdd("myzset","$count","$search");
            $arr=Db::query("select g.id,g.goods_id, g.name,brand.brand_name,brand.id as brand_id,goods_cate.brand_name as goods_brand_name from goods as g join brand ON g.brand_id=brand.id JOIN goods_cate on g.goods_id=goods_cate.id where g.name like '%$search%' limit 0,300");
            $arr=json_encode($arr);
            
        if ($count>10) {

                $redis->hSet('myhash','list',$arr);
                $arr=$redis->hGet('myhash','list');
                $arr=json_decode($arr);
            }else{
                 $arr=Db::query("select g.id,g.goods_id, g.name,brand.brand_name,brand.id as brand_id,goods_cate.brand_name as goods_brand_name from goods as g join brand ON g.brand_id=brand.id JOIN goods_cate on g.goods_id=goods_cate.id limit 0,300");
            
            }
       }
       

        $res=['code'=>'0','status'=>'ok','data'=>$arr,'row'=>$haxi];
        echo $json=json_encode($res);
    }
    public function search(){
       
                
    }
    public function add(){
    	$arr=Db::query("select * from brand");
    	$this->assign('arr',$arr);
    	return $this->fetch();
    }
        private function getTree($arr,$pid = 0, $level = 0){


        static $list = [];


        foreach ($arr as $key => $value){

            
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                   $flg = str_repeat('&nbsp;&nbsp;&nbsp;',$level);
                // 更新 名称值
                    $value['brand_name'] =$flg.$value['brand_name'];

                // 输出 名称
//                    echo $value['name']."<br/>";
                    $m_id=$value['id'];
               
               
                // echo "<button>删除</button>";
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($arr[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($arr, $value['id'], $level+1);

            }
            
        }
        return $list;
    }
    function goods_show(){
        $arr = Db::table('goods_cate')->select();
        
        $sql=$this->getTree($arr);
        $res=['code'=>'0','status'=>'ok','data'=>$sql];
  		echo $json=json_encode($res);
  		die;
    }
    function addaction(){
    	$data=Request::post('');
    	 $validate = new \app\admin\validate\Product;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
    	$name=Request::post('name');
    	$brand_id=Request::post('brand_id');
    	$goods_id=Request::post('goods_id');
    	$data=['name'=>$name,'brand_id'=>$brand_id,'goods_id'=>$goods_id];
    	Db::table('goods')->insert($data);
    	$res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
  		echo $json=json_encode($res);

    }
    function delete(){
    	$id=Request::post('id');
    	Db::table('goods')->where('id',$id)->delete();
    	Db::table('goods_img')->where('goods_id',$id)->delete();
    	$res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
  		echo $json=json_encode($res);
    }
    public function update(){
    	$data=Request::post('');
    	 $validate = new \app\admin\validate\Product;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
    	$id=Request::post('id');
    	$name=Request::post('name');
    	$brand_id=Request::post('brand_id');
    	$goods_id=Request::post('goods_id');
    	$data=['name'=>$name,'brand_id'=>$brand_id,'goods_id'=>$goods_id];
    	Db::table('goods')->where('id',$id)->update($data);
    	$res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
  		echo $json=json_encode($res);
    }
     // public function test(){
     //     $arr=[];
     //     for ($i=0;$i<10000;$i++){
     //         $num=rand(3, 10);
     //         $b=$this->getChar($num);
     //         $arr[]=$b;
     //     }
     //     $arr1=implode($arr,"'),('");
     //     $sql="insert into `goods`(`name`) values('$arr1')";
     //     Db::query($sql);
     // }

    
}
