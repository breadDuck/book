<?php

namespace app\index\controller;

use app\common\controller\IndexBase;
use app\index\model\Book;

class Index extends IndexBase
{
    public function index()
    {
        $hotList = Book::scope('hotList')->select();
        $bookList = model('Book')->paginate(8);
        $this->assign([
            'hotList' => $hotList,
            'bookList' => $bookList
        ]);
        return $this->fetch();
    }
}
