<?php

namespace app\admin\validate;

use think\Validate;

class Product extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */ 
    protected $rule = [
    'name'  => 'require|token',
    
    'brand_id'   => 'require',
    'goods_id'   => 'require',
    

    
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */ 
}
