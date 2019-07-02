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
      $Permissioncategory = new PermissioncateModel;
      $obj=$Permissioncategory->select();
      $arr=json_decode($obj,true);
      $this->assign('arr',$arr);
    	return $this->fetch();
    }

   public function show()
  {
   
    $arr=Db::query("select p.id,p.name, pc.name as pc_name from permission as p inner join permission_category as pc on p.category_id=pc.id");
    
  	$res=['code'=>'0','status'=>'ok','data'=>$arr];
  	echo $json=json_encode($res);
	
  }

    
}