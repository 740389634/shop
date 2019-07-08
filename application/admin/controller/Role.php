<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use think\Db;
class Role extends Common
{
	public function index()
    {
     
    	return $this->fetch();
    }
    //展示
   public function show()
  {
    $arr=Db::query("select * from role ");

     // $arr=Db::query("select * from permission as p inner join permission_category as pc on p.category_id=pc.id");
    
  	$res=['code'=>'0','status'=>'ok','data'=>$arr];
  	echo $json=json_encode($res);
	
  }

  public function add(){
  	
 		return $this->fetch();
  }
  public function roleshow(){
  	$id=Request::get('id');
  		$row=Db::query("select permission_id from role_permission where role_id='$id' ");
  		$arr=Db::query("select p.id,p.name,p.path,p.description, p.category_id,pc.id as pc_id,pc.name as pc_name  FROM permission as p left join permission_category as pc on p.category_id=pc.id  ");
  		
  		$sql=[];
  		foreach ($arr as $key => $value) {
  			$sql[$value['pc_name']][]=$value;
  		}
  		$res=['code'=>$row,'status'=>'ok','data'=>$sql];

  		echo $json=json_encode($res);
  }
  public function update(){

  		$id=Request::post('id');
  		$name=Request::post('name');
  		$description=Request::post('description');
  		$permission_id=Request::post('permission_id');

  		// $__token__=Request::post('__token__');
  		// $validate = new \app\admin\validate\Role;
    //     if (!$validate->check($__token__)) {
    //        $res=['code'=>'2','status'=>'error','data'=>$validate->getError()];
    //         echo json_encode($res);
    //         die;
    //     }
  		$arr=Db::query("select * from role where name='$name'");
  		if (empty($arr)||!empty($arr)&&$arr[0]['id']==$id) {
  			$data=['name'=>$name,'description'=>$description];
  			Db::table('role')->where('id',$id)->update($data);
  			// $res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
  			// echo $json=json_encode($res);
  			
  			$sql=Db::table('role_permission')->where('role_id',$id)->delete();
	  		$row=explode(',', $permission_id);
	  			array_shift($row);

	  			foreach ($row as $key => $value) {	  				
	  				$arr=Db::query("insert into role_permission (`role_id`,`permission_id`) values ('$id','$value')");	
	  			}
	  		$res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
  			echo $json=json_encode($res);
	  	}else{
	  		$res=['code'=>'1','status'=>'ok','data'=>'名称已存在'];
	  		echo $json=json_encode($res);
	  	}
  		
  }

  	public function addAction(){
  		// $id=Request::post('id');
  		// $name=Request::post('name');
  		// $description=Request::post('description');
  		// $permission_id=Request::post('permission_id');
  		$data=Request::post('');
  		$name=$data['name'];
  		$description=$data['description'];
  		$permission_id=$data['permission_id'];
  		$description=Request::post('description');

      	$validate = new \app\admin\validate\Role;
        if (!$validate->check($data)) {
           $res=['code'=>'2','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
        unset($data['__token__']);
  		$arr=explode(',', $permission_id);
  		array_shift($arr);
  		
  		$id=implode(',', $arr);   
        $sql=Db::query("select * from role where name='$name'");
        $rbac = new Rbac();
        if (empty($sql)) {
			$rbac->createRole([
				'name' => $name,
				'description' => $description,
				'status' => 1
			], $permission_id);
			$res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
  			echo $json=json_encode($res);
        }else{
        	$res=['code'=>'1','status'=>'error','data'=>'角色名字不能重复'];
  			echo $json=json_encode($res);
		}
    }
    function delete(){
    	$id=Request::get('id');
    	$arr=Db::query("delete from role where id='$id'");
      $arr=Db::query("delete from role_permission where role_id='$id'");
    	$res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
  		echo $json=json_encode($res);
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
      $rbac->delRole($arr);
      $res=['code'=>'1','status'=>'ok','data'=>'删除成功'];
        echo json_encode($res);
      
    }
}