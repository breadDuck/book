<?php

namespace app\index\model;

use think\Model;

class Comment extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $insert = ['admin_id'];
    public function setAdminIdAttr()
    {
        return session('bookuser.id');
    }
    public function getViewAttr($val)
    {
        $status = [
            'good' => '非常棒',
            'common' => '一般般',
            'bad' => '垃圾'
        ];
        return $status[$val];
    }
    public function getAdminIdAttr($val)
    {
        $info=model('User')->find($val);
        return $info['name'];
    }
}
