<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Goodscate extends Common
{
    public function index()
    {
          return $this->fetch();
        
    }
    private function getTree($arr,$pid = 0, $level = 0){


        static $list = [];

echo "<ul>";
        foreach ($arr as $key => $value){

            
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
//                    $flg = str_repeat('|--',$level);
                // 更新 名称值
                    $value['brand_name'] = $value['brand_name'];

                // 输出 名称
//                    echo $value['name']."<br/>";
                    $m_id=$value['id'];
                echo "<li value='$m_id'>".$value['brand_name']."<button onclick='my_delete(".$value['id'].",".$value['pid'].")'>删除</button>"."<button onClick='modaldemo(".$value['id'].",\"".$value['brand_name']."\")'>编辑</button>"."</li>";
               
                // echo "<button>删除</button>";
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($arr[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($arr, $value['id'], $level+1);

            }
            
        }
        echo "</ul>";
        return $list;
    }
    function show(){
        $arr = Db::table('goods_cate')->select();
        
        $this->getTree($arr);
    }
    public function addaction(){
        $pid=Request::post('pid');
        $name=Request::post('name');
        if (empty($name)) {
             $res=['code'=>'1','status'=>'error','data'=>'不能为空'];
            echo $json=json_encode($res);
            die;
        }
      //   $data=Request::post('');

      // $validate = new \app\admin\validate\Goodscate;
      //   if (!$validate->check($data)) {
      //      $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
      //       echo json_encode($res);
      //       die;
      //   }
       $arr= Db::query("select * from goods_cate where brand_name='$name'");
        if (empty($arr)) {
            $data=['brand_name'=>$name,'pid'=>$pid];
            Db::table('goods_cate')->insert($data);
            $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
            echo $json=json_encode($res);
            die;
        }else{
             $res=['code'=>'1','status'=>'error','data'=>'不能重复'];
            echo $json=json_encode($res);
            die;
        }
       
    }
    public function delete(){
       $id=Request::post('id');
        $pid=Request::post('pid');
        // $pid=Request::post('pid');
        // echo $pid;die;
       $arr=Db::query("delete from goods_cate where id='$id'");
       $res=$this->get_all_child($id);
       echo json_encode($res);
    }
    public function get_all_child($id){
        $arr=Db::query("select * from goods_cate where pid=$id");
        if (empty($arr)) {
            $res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
            return $res;            
        }else{

            foreach ($arr as $key => $value) {
                $id=$value['id'];
                Db::query("delete from goods_cate where id='$id'");
                $this->get_all_child($value['id']);
            }  
        }
    }
    public function update(){
        $id=Request::post('id');
        $name=Request::post('name');
        $data=Request::post('');

      $validate = new \app\admin\validate\Goodscate;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
       $arr= Db::query("select * from goods_cate where id='$id'");
       if (!empty($arr)) {
           $data=['brand_name'=>$name];
           Db::table('goods_cate')->where('id',$id)->update($data);
           $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
            echo $json=json_encode($res);
            die;
       }else{
            $res=['code'=>'1','status'=>'error','data'=>'添加失败'];
            echo $json=json_encode($res);
            die;
       }
    }
        

    
}