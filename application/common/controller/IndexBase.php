<?php

namespace app\common\controller;

use think\Controller;
use app\index\model\Category;

class IndexBase extends Controller
{
    protected $beforeActionList = [
        'check' => ['only' => 'read']
    ];

    public function _initialize()
    {
        $categoryList = Category::all();
        $this->assign(['categoryList' => $categoryList]);
    }

    public function check()
    {
        if (!session('?bookuser')) {
            $this->error('您尚未登录，请先登录！', url('index/user/login'));
        }
    }
}
