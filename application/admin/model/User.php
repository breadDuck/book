<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'updated_at';
    protected $createTime = 'created_at';
    protected $insert=['money'=>150]; //管理员创建用户时给150金币
    public function setPasswordAttr($v)
    {
        return md5($v);
    }
}
