<?php

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{  
    protected $rule = [
        'name' => 'require|length:2,20|unique:user',
        'password' => 'require|min:6',
        'email' => 'require|email',
        'user_rank' => 'require|in:1,2,3'
    ];
    protected $message = [
        'name.require' => '请填写用户名',
        'name.length' => '用户名长度须在2-20位之间',
        'name.unique' => '已存在此用户名',
        'password.require' => '请填写密码',
        'password.min' => '密码长度至少6位',
        'email.email' => '邮箱格式不正确',
        'user_rank.in' => '用户等级只有1，2，3级'
    ];
}
