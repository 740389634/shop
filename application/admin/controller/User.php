<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use think\Db;
class User extends Common
{
	public function index()
    {
     $row=Db::query("select * from role ");
     $this->assign('arr',$row);
    	return $this->fetch();
    }
    //展示
   public function show()
  {
    $arr=Db::query("select user.id,user.password, user.user_name,user.mobile,role.name,role.id as role_id  from user left join user_role on user.id=user_role.user_id join role on user_role.role_id=role.id ");
    

     // $arr=Db::query("select * from permission as p inner join permission_category as pc on p.category_id=pc.id");
    
  	$res=['code'=>'0','status'=>'ok','data'=>$arr];
  	echo $json=json_encode($res);
	
  }

  public function add(){
  	
 		return $this->fetch();
  }
  public function roleshow(){
  	$id=Request::get('id');
    $row=Db::query("select * from role");
  		// $row=Db::query("select user_role.user_id,user_role.role_id,role.id as r_id,role.`name` from user_role left join role on user_role.role_id=role.id ");
    
  		$arr=Db::query("select p.id,p.name,p.path,p.description, p.category_id,pc.id as pc_id,pc.name as pc_name  FROM permission as p left join permission_category as pc on p.category_id=pc.id  ");
  		
  		$sql=[];
  		foreach ($arr as $key => $value) {
  			$sql[$value['pc_name']][]=$value;
  		}
  		$res=['code'=>$row,'status'=>'ok','data'=>$sql];

  		echo $json=json_encode($res);
  }
  public function permission_show(){

  		$id=Request::post('id');
  		$name=Request::post('name');
  		$description=Request::post('description');
  		$permission_id=Request::post('permission_id');

  		// $__token__=Request::post('__token__');
  		// $validate = new \app\admin\validate\User;
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
  		  $data=Request::post('');

  		$user_name=Request::post('user_name');
  		$password=Request::post('password');
      $phone=Request::post('phone');
      $name=Request::post('name');

      
       
        $arr=Db::query("select * from user where user_name='$user_name'");
          $validate = new \app\admin\validate\User;
        if (!$validate->check($data)) {
           $res=['code'=>'2','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
        if (empty($password)) {
           $res=['code'=>'3','status'=>'error','data'=>'密码不能为空'];
          echo $json=json_encode($res);
          die;
        }
        if (empty($phone)) {
           $res=['code'=>'4','status'=>'error','data'=>'手机号不能为空'];
          echo $json=json_encode($res);
          die;
        }
      if (empty($arr)) {
        $data=['user_name'=>$user_name,'password'=>$password,'mobile'=>$phone];
        Db::table('user')->insert($data);
       
         $sql=Db::query("select * from user where user_name='$user_name'");
         $id=$sql[0]['id'];
         $rbac = new Rbac();
         $uname[]=$name;
         $rbac->assignUserRole($id, $uname);
         $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        echo $json=json_encode($res);
      }else{
        $res=['code'=>'1','status'=>'error','data'=>'用户已存在'];
        echo $json=json_encode($res);
      }
    }
    // function pc_delete(){
    // 	$id=Request::get('id');
    // 	$arr=Db::query("delete from role where id='$id'");
    // 	$res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
  		// echo $json=json_encode($res);
    // }
    function update(){
       $data=Request::post('');
      $id=Request::post('id');
      $user_name=Request::post('user_name');
      $password=Request::post('password');
      $mobile=Request::post('mobile');
      $r_name=Request::post('r_name');
      $validate = new \app\admin\validate\User;
       if (!$validate->check($data)) {
           $res=['code'=>'2','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
        if (empty($password)) {
           $res=['code'=>'3','status'=>'error','data'=>'密码不能为空'];
          echo $json=json_encode($res);
          die;
        }
        if (empty($mobile)) {
           $res=['code'=>'4','status'=>'error','data'=>'手机号不能为空'];
          echo $json=json_encode($res);
          die;
        }
      
      $sql=Db::query("select * from user where user_name='$user_name'");
      
      if (empty($sql)||!empty($sql)&&$sql[0]['id']==$id) {
        $where=['user_name'=>$user_name,'password'=>$password,'mobile'=>$mobile];
        $mysqli=Db::table('user')->where('id','=',$id)->update($where);
        $rbac=new Rbac;
        $tid[]=$r_name;
        $rbac->assignUserRole($id, $tid);
        $res=['code'=>'0','status'=>'ok','data'=>"修改成功"];
      }else{
          $res=['code'=>'1','status'=>'error','data'=>"角色名重复"];

      }
      $json=json_encode($res);
       echo $json;
    }
    function delete(){
      $id=Request::get('id');
      $arr=Db::query("delete from user where id='$id'");
      $arr=Db::query("delete from user_role where user_id='$id'");
      $res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
      echo $json=json_encode($res);
    }
}