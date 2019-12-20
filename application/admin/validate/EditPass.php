<?php

namespace app\admin\validate;

use think\Validate;

class EditPass extends Validate
{  
    protected $rule = [
        'password' => 'require|min:6',
        'repassword' => 'confirm:password'
    ];
    protected $message = [
        'password.require' => '请填写新密码！',
        'password.min' => '密码长度不得小于六位',
        'repassword.confirm' => '两次密码输入不一致！', 
    ];
}
