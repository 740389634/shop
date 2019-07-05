<?php

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */ 
    protected $rule = [
    'user_name'  => 'require|token',
    
    // 'password'   => 'require',
    // 'mobile'   => 'require',
    

    
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */ 
    // protected $message = [
    //     'user_name.require' => '名称必须填写',
    //     // 'name.max'     => '名称最多不能超过50个字符',
    //     // 'name.mall'     => '名称不能低于一个字符',
    //     'password.require'   => '密码必须填写',
    //     'mobile.require'   => '手机号必须填写',
    //     // 'description.max'  => '描述最多不能超过200个字符',
    //     // 'description.mall'     => '描述不能低于一个字符',
        
    // ];
}
