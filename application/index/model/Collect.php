<?php
namespace app\index\model;

use think\Model;

class Collect extends Model
{   
    protected $insert=['admin_id']; //自动添加此字段
    public function verifyCollect($book_id,$admin_id)
    {
        return (bool)$this->where('book_id',$book_id)->where('admin_id',$admin_id)->find();
    }
    public function cancelCollect($book_id,$admin_id)
    {
        return $this->where('book_id',$book_id)->where('admin_id',$admin_id)->delete();
    }
    public function detail()
    {
        return $this->hasOne('Book','id','book_id');
    }
    public function setAdminIdAttr()
    {
        return session('bookuser.id');
    }

}