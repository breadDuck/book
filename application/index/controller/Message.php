<?php

namespace app\index\controller;

use app\common\controller\IndexBase;

class Message extends IndexBase
{
    public function index()
    {
        $mId = model('Message')->getNewMessageId();
        if ($mId && session('?bookuser')) {
            model('User')->update([
                'id' => session('bookuser.id'),
                'message_id' => $mId
            ]);
        }
        session('new', 0);
        $message = model('Message')->getMessage();
        $this->assign('message', $message);
        return view();
    }
}
