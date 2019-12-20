<?php

namespace app\admin\model;

use think\Model;

class Book extends Model
{
    protected $createTime='created_at';
    protected $updateTime='updated_at';
    protected $autoWriteTimestamp=true;
    protected $insert=['admin_id']; //添加时，自动添加的字段
    public function author()
    {
        return $this->belongsTo('User','admin_id');
    }
    public function category()
    {
        return $this->belongsTo('Category','category_id');
    }
    public function getIsUploadAttr($value)
    {
        $status = [
            0=>'<span class="text-primary">URL</span>',
            1=>'<span class="text-danger">文件</span>'
        ];
        return $status[$value];
    }
    public function setAdminIdAttr()
    {
        return session('bookuser.id');
    }
}
