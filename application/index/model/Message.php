<?php

namespace app\index\model;

use think\Model;

class Message extends Model
{
    protected $autoWriteTimestamp = true;
    public function saveMessage($admin_id, $book_id)
    {
        $data = [
            'creator_id' => session('bookuser.id'),
            'target_id' => $admin_id,
            'book_id' => $book_id
        ];
        return $this->save($data);
    }
    public function getMessage()
    {
        return $this->where('target_id', session('bookuser.id'))->order('id', 'desc')->paginate(10);
    }
    public function setReadMessage()
    {
        $this->where('target_id', session('bookuser.id'))->where('id', '<=', session('user.message_id'))->update(['have_read' => 1]);
    }
    public function getNewMessageId()
    {
        return $this->field('id')->where('target_id', session('bookuser.id'))->order('id', 'desc')->limit(1)->find()['id'];
    }
    public function book()
    {
        return $this->hasOne('Book', 'id', 'book_id');
    }
    public function creator()
    {
        return $this->hasOne('User', 'id', 'creator_id');
    }
}
