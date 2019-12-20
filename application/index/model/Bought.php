<?php
namespace app\index\model;

use think\Model;

class Bought extends Model
{
    protected $insert=['admin_id'];
    public function setAdminIdAttr()
    {
        return session('bookuser.id');
    }
    public function verifyBought($book_id,$admin_id)
    {
        return (bool)$this->where('book_id',$book_id)->where('admin_id',$admin_id)->find();
    } 
    public function detail()
    {
        return $this->belongsTo('Book','book_id');
    }
}