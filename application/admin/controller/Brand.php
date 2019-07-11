<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Brand extends Common
{
    public function index()
    {
       return $this->fetch();
    }
    public function show(){
    	$arr=Db::query("select * from brand");
    	$res=['code'=>'0','status'=>'ok','data'=>$arr];
  		echo $json=json_encode($res);
  		die;
    }
    //添加
    public function addaction(){
    	
 		$brand_name=Request::post('brand_name');
    	$website=Request::post('website');
    	$describe=Request::post('describe');
    	
    	 $data=Request::post('');

      $validate = new \app\admin\validate\Brand;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
    	 // 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('brand_logon');
	    // 移动到框架应用根目录/imgas/ 目录下
	   if ($file) {
	   		    $info = $file->move( 'static/imgas');
		   	if($info){
		        $getSaveName=str_replace("\\","/",$info->getSaveName());
		        $arr=Db::query("select * from brand where brand_name='$brand_name' or website='$website'");
		        if (empty($arr)) {
		        	$data=['brand_name'=>$brand_name,'website'=>$website,'describe'=>$describe,'img'=>$getSaveName];
			        Db::table('brand')->insert($data);
			        $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
		  			echo $json=json_encode($res);
		  			die;
		        }else{
		        	 $res=['code'=>'1','status'=>'error','data'=>'品牌或网址有重复'];
		  			echo $json=json_encode($res);
		  			die;
		        }
		        
		    }else{
		    	
		        echo $file->getError();
		        die;
		    }
	   }else{
	   		$res=['code'=>'1','status'=>'error','data'=>'文件不能为空'];
		  	echo $json=json_encode($res);
		  	die;
	   }
	   

	}
    public function delete(){
    	$id=Request::post('id');
      $arr=Db::table('brand')->where('id',$id)->select();
      $file = "static/imgas/".$arr[0]['img'];
         if (!unlink($file)){
             $res=['code'=>'1','status'=>'error','data'=>'删除失败'];
              echo $json=json_encode($res);
             
          }else{
              Db::table('brand')->where('id',$id)->delete();
              $res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
              echo $json=json_encode($res);
             die;
             
          }
    	
    }
    public function update(){
    	$id=Request::post('id');
    	$brand_name=Request::post('name');
    	$website=Request::post('website');
    	$describe=Request::post('describe');
    	 // $data=Request::post('');

      // $validate = new \app\admin\validate\Brand;
      //   if (!$validate->check($data)) {
      //      $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
      //       echo json_encode($res);
      //       die;
      //   }
    	if (empty($brand_name)) {
    		$res=['code'=>'1','status'=>'error','data'=>'名称不能为空'];
  			echo $json=json_encode($res);
  			die;
    	}
    	if (empty($website)) {
    		$res=['code'=>'1','status'=>'error','data'=>'网址不能为空'];
  			echo $json=json_encode($res);
  			die;
    	}
    	if (empty($describe)) {
    		$res=['code'=>'1','status'=>'error','data'=>'描述不能为空'];
  			echo $json=json_encode($res);
  			die;
    	}
    	$sql=Db::query("select * from brand where brand_name='$brand_name'");
    	if (empty($sql)) {
    		$data=['brand_name'=>$brand_name,'website'=>$website,'describe'=>$describe];
    		Db::table('brand')->where('id',$id)->update($data);
    		$res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
  			echo $json=json_encode($res);
  			die;
    	}else{
    		$res=['code'=>'1','status'=>'error','data'=>'名称,网址,描述有重复'];
  			echo $json=json_encode($res);
  			die;
    	}

    }
    public function update_img(){
      $id=Request::post('id');
      $chooseImage = request()->file('chooseImage');
      $arr=Db::table('brand')->where('id',$id)->select();

        if ($chooseImage) {
            $info = $chooseImage->move( 'static/imgas');
          if($info){

            $getSaveName=str_replace("\\","/",$info->getSaveName());
            
            $data=['img'=>$getSaveName];
            Db::table('brand')->where('id',$id)->update($data);
            

           $file = "static/imgas/".$arr[0]['img'];
         
           if (!unlink($file)){
             $res=['code'=>'1','status'=>'error','data'=>'修改失败'];
              echo $json=json_encode($res);
             
            }else{
              $res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
              echo $json=json_encode($res);
              die;
             
            }

          }else{
          
            echo $file->getError();
            die;
          }
      }else{
        $res=['code'=>'1','status'=>'error','data'=>'文件不能为空'];
        echo $json=json_encode($res);
        die;
      }

    }
   
}
