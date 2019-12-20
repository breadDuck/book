<?php

namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use think\Request;

class Pages extends AdminBase
{
    /**
     * 显示已上传书列表和其章节(章节作为跳转按钮去edit界面)
     *
     * @return \think\Response
     */
    public function editList()
    {
        $id = session('bookuser.id');
        $originList = model('Book')->field('id,title')->where('admin_id', $id)->select();
        $list = [];
        foreach ($originList as $v) {
            $item = $v->toArray();
            $item['pages'] = model('Pages')->field('title,id')->where('book_id', $item['id'])->select();
            $list[] = $item;
        }
        $this->assign('list', $list);
        return view();
    }

    /**
     * 显示已上传书列表，书作为跳转按钮去create界面
     *
     */
    public function addList()
    {
        $id = session('bookuser.id');
        $list = model('Book')->field('id,title,last_title')->where('admin_id', $id)->select();
        $this->assign('list', $list);
        return view();
    }

    /**
     * 显示创建新章节页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $id = input('param.id');
        $book = model('Book')->where('id', $id)->find();
        $this->assign('book', $book);
        return view();
    }

    /**
     * 保存新章节
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->param();
        if (!$data['title']) {
            $count = model('Pages')->where('book_id', $data['book_id'])->count('id') + 1;
            $data['title'] = '第' . $count . '章';
        }
        model('Book')->where('id', $data['book_id'])->update(['last_title' => $data['title']]); //更新本书的“上一次更新”
        // echo '<pre>';
        // print_r($data);
        // die;
        $re = model('Pages')->save($data);
        if ($re) {
            $this->success('提交成功', url('admin/pages/addList'));
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $page = model('Pages')->find($id);
        $book = model('Book')->field('id,title')->find($page['book_id']);
        $this->assign([
            'page' => $page,
            'book' => $book
        ]);
        return view();
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
        $data = $request->param();
        Db::startTrans();
        $re = model('Pages')->isUpdate(true)->save($data);
        $re2 = model('Book')->where('id', $data['book_id'])->update(['last_title' => $data['title']]);
        if ($re && $re2) {
            Db::commit();
            $this->success('修改成功', url('admin/pages/editList'));
        } else {
            Db::rollback();
            $this->error('操作失败');
        }
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
