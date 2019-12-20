<?php
namespace app\index\model;

use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp=true;
    protected $createTime='created_at';
    protected $updateTime='updated_at';
    protected $insert=['money'=>200]; //注册用户时给200金币
    public function setPasswordAttr($v)
    {
        return md5($v);
    }
    
    public function getLogin($name,$pass)
    {
        return $this->where('name','=',$name)->where('password','=',md5($pass))->find();
    }
    public function buy($price)
    {
        $id=session('bookuser.id');
        $money=$this->get($id)->money;
        if ($money>=$price) {
           $money-=$price;
           return $this->where('id',$id)->update(['money'=>$money]);
        }else{
            return false;
        }
    }
}