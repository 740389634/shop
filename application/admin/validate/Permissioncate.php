<?php

namespace app\admin\validate;

use think\Validate;

class Permissioncate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
    'name'  => 'require|max:50|mall:1',
    
    'description'   => 'number|max:200|mall:1',
    

    
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过50个字符',
        'name.mall'     => '名称不能低于一个字符',
        'description.number'   => '描述必须',
        'description.max'  => '描述最多不能超过200个字符',
        'description.mall'     => '描述不能低于一个字符',
        
    ];
}
