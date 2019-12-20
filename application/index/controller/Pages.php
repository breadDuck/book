<?php

namespace app\index\controller;

use app\common\controller\IndexBase;
use think\Request;

class Pages extends IndexBase
{
    /**
     * 显示某书的章节列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $book_id = input('param.id');
        $pages = model('Pages')->field('id,title')->where('book_id', $book_id)->paginate(50);
        $book = model('Book')->find($book_id);
        if ($book['book_key'] != input('param.key')) {
            $this->error('别想着不交钱白嫖！');
        }
        $this->assign([
            'pages' => $pages,
            'book' => $book
        ]);
        return view();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的章节
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $book_id = input('param.book_id');
        $book = model('Book')->find($book_id);
        $page = model('Pages')->find($id);
        $this->assign(['page' => $page, 'book' => $book]);
        return view();
    }

    /**
     * 显示下一章节
     *
     */
    public function nextPage($id)
    {
        $book_id = input('param.book_id');
        $book = model('Book')->find($book_id);
        $page = model('Pages')->where('book_id', $book_id)->where('id', '>', $id)->find();
        if (!$page) {
            $this->error('已经是最新一章啦！');
        }
        $this->assign(['page' => $page, 'book' => $book]);
        return $this->fetch('read');
    }

    /**
     * 显示上一章节
     *
     */
    public function lastPage($id)
    {
        $book_id = input('param.book_id');
        $book = model('Book')->find($book_id);
        $page = model('Pages')->where('book_id', $book_id)->where('id', '<', $id)->order('id','desc')->find();
        if (!$page) {
            $this->error('已经是第一章啦！');
        }
        $this->assign(['page' => $page, 'book' => $book]);
        return $this->fetch('read');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
