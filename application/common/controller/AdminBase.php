<?php

namespace app\common\controller;

use think\Controller;

class AdminBase extends Controller
{
    protected $beforeActionList = [
        'check'  
        // 'check' => ['except' => 'add'] //可设置前置操作，为一些页面减去前置方法
        // 'check' => ['only' => 'add,list'] //
    ];

    public function check()
    {
        if (!session('?bookuser')) {
            $this->error('您尚未登录，请先登录！', url('index/user/login'));
        }
    }
}
