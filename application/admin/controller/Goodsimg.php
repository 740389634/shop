<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Goodsimg extends Common
{
    public function index()
    {
    	$goods_id=Request::get('id');
    	$this->assign('goods_id',$goods_id);
       return $this->fetch();
    }
    public function show(){
    	$goods_id=Request::post('goods_id');
    	$arr=Db::query("select * from goods_img where goods_id='$goods_id'");
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
  		echo $json=json_encode($res);
  		die;
    }
    public function addaction(){
    // 获取表单上传文件
    	$goods_id=Request::post('goods_id');
    	
    $files = request()->file('file');

    foreach($files as $file){

        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move( 'static/imgas/img');

        if($info){

           $getSaveName=str_replace("\\","/",$info->getSaveName());
            $name=$info->getFileName();
           
            $big_img=date("Ymd").'/'.'big'.$name;

            $middle_img=date("Ymd").'/'.'middle'.$name;
            $small_img=date("Ymd").'/'.'small'.$name;
          
            $img = \think\Image::open('static/imgas/img/'.$getSaveName);     
      			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
      			$img->thumb(150, 150)->save('static/imgas/img/'.$big_img);

      			$img->thumb(100, 100)->save('static/imgas/img/'.$middle_img);
      			$img->thumb(50, 50)->save('static/imgas/img/'.$small_img);
			
            $data=['big_img'=>$big_img,'middle_img'=>$middle_img,'small_img'=>$small_img,'goods_id'=>$goods_id,'graph_img'=>$getSaveName];
            Db::table('goods_img')->insert($data);
            
            

        }else{
            // 上传失败获取错误信息
            // echo $file->getError();
        }    
    }
            $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
             echo $json=json_encode($res);
	}
	 public function delete(){
    	$id=Request::post('id');
      	$arr=Db::table('goods_img')->where('id',$id)->select();
      	$big_img=$arr[0]['big_img'];
        $small_img=$arr[0]['small_img'];
      	$middle_img=$arr[0]['middle_img'];
        $graph_img=$arr[0]['graph_img'];
      	
     	  $big = "static/imgas/img/".$big_img;
        $small = "static/imgas/img/".$small_img;
        $middle = "static/imgas/img/".$middle_img;
        $graph = "static/imgas/img/".$graph_img;
         if (!unlink($big) ||!unlink($small)||!unlink($middle)||!unlink($graph)){
             $res=['code'=>'1','status'=>'error','data'=>'删除失败'];
              echo $json=json_encode($res);
              die;
             
          }else{
              Db::table('goods_img')->where('id',$id)->delete();
              $res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
              echo $json=json_encode($res);
             die;
             
          }
    	
    }
}
