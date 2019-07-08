<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use think\Db;
use app\admin\model\Permission as PermissionModel;
use app\admin\model\Permissioncate as PermissioncateModel;
class Permission extends Common
{
	public function index()
    {
      $arr=Db::table('permission_category')->select();
      $this->assign('arr',$arr);
    	return $this->fetch();
    }
    //展示
   public function show()
  {
    $arr=Db::query("select p.id,p.name,p.path,p.description, p.category_id,pc.id as pc_id,pc.name as pc_name  FROM permission as p left join permission_category as pc on p.category_id=pc.id");

     // $arr=Db::query("select * from permission as p inner join permission_category as pc on p.category_id=pc.id");
    
  	$res=['code'=>'0','status'=>'ok','data'=>$arr];
  	echo $json=json_encode($res);
	
  }
  //添加
  public function addAction(){
      $data=Request::post('');

      $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
        unset($data['__token__']);
      $rbac = new Rbac();
      $arr=$rbac->getPermission([['name', '=', $data['name']]]);
      $path=$rbac->getPermission([['path', '=', $data['path']]]);
      // $sql=Db::query("select * from permission where name='$name' or path='$path' ");
      if (empty($arr)&&empty($psth)) {
        $rbac->createPermission([
        'name' => $data['name'],
        'description' => $data['description'],
        'status' => 1,
        'type' => 1,
        'category_id' => $data['cateid'],
        'path' => $data['path'],
        ]);
        $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        echo json_encode($res);
        die;
      }else{
        $res=['code'=>'2','status'=>'error','data'=>'名称和路径不能重复'];
        echo json_encode($res);
      }
  }
  //删除
  public function delete(){
    $id=Request::post('id');
    Db::table('permission')->where('id',$id)->delete();
    $res=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo json_encode($res);
  }
  //批量删除
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
      $rbac->delPermission($arr);
      $res=['code'=>'1','status'=>'ok','data'=>'删除成功'];
        echo json_encode($res);
      
    }
    //修改
    public function update(){
      $data=Request::post('');
      $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
           $res=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo json_encode($res);
            die;
        }
        unset($data['__token__']);
      $rbac = new Rbac();

      $name=Request::post('name');
      // var_dump($name);die;
      $path=Request::post('path');
      $id=Request::post('id');
      $sql=Db::query("select * from permission where name='$name' or path='$path' ");
      
      $arr=$rbac->getPermission([['name', '=', $data['name']]]);
     
      $path=$rbac->getPermission([['path', '=', $data['path']]]);
      if (empty($sql)) {
        Db::table('permission')->update($data);
        $res=['code'=>'0','status'=>'ok','data'=>'修改成功'];
        echo json_encode($res);
        die;
      }else{
        foreach ($sql as $key => $value) {
          if ($value['id']!=$id) {
            $res=['code'=>'2','status'=>'error','data'=>'名称或路径不能重复'];
            echo json_encode($res);   
            die;
          }
        }
          Db::table('permission')->update($data);
          $res=['code'=>'1','status'=>'ok','data'=>'修改成功'];
          echo json_encode($res);
          die;
      }
    }
}