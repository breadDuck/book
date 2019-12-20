<?php

namespace app\index\controller;

use app\common\controller\IndexBase;
use app\index\model\Book;
use app\index\model\Category;
use think\Db;

class Article extends IndexBase
{
    public function index()
    {
        $id = input('param.id');
        Book::get($id)->setInc('browse_times');

        $hotList = Book::scope('HotList')->select();
        $newList = Book::scope('NewList')->select();
        $book = Book::get($id);
        // $categoryList = Category::scope('all')->select();
        $collected = false;
        $bought = false;
        if (session('?bookuser')) {
            $admin_id = session('bookuser.id');
            $collected = model('Collect')->verifyCollect($id, $admin_id); //确认本书有无被用户收藏
            $bought = model('Bought')->verifyBought($id, $admin_id); //确认本书有无被购买
        }
        $this->assign([
            'hotList' => $hotList,
            'newList' => $newList,
            'book' => $book,
            'collected' => $collected,
            'bought' => $bought
        ]);
        return $this->fetch();
    }
    public function list()
    {
        $id = input('param.id');
        // $categoryList = Category::scope('all')->select();
        $category = Category::get($id);
        $categoryName = $category->title; //获取当前分类名
        $bookList = $category->bookList()->order('browse_times', 'desc')->paginate(6);
        $newList = $category->bookList()->field('title,id')->order('id', 'desc')->select();
        $this->assign([
            'categoryName' => $categoryName,
            'bookList' => $bookList,
            'newList' => $newList
        ]);
        return $this->fetch();
    }
    public function collect()
    {
        if (!session('?bookuser')) {
            return json_encode(['re' => false]);
        }
        $data = input('param.');
        $re = ['re' => model('Collect')->save($data)];
        return json_encode($re);
    }
    public function cancel()
    {
        $book_id = input('param.book_id');
        $admin_id = session('bookuser.id');
        $re = ['re' => model('Collect')->cancelCollect($book_id, $admin_id)];
        return json_encode($re);
    }
    public function adminCancel() //后台界面实行的取消收藏操作
    {
        $book_id = input('param.book_id');
        $admin_id = session('bookuser.id');
        $re = model('Collect')->cancelCollect($book_id, $admin_id);
        if ($re) {
            $this->redirect(url('admin/index/index'));
        } else {
            $this->error('操作失败！');
        }
    }
    public function buy()
    {
        if (!session('?bookuser')) {
            return json(['re' => false, 'info' => '您尚未登录，请先登录']);
        }
        $data = input('param.');
        // var_dump($_POST['money']);
        Db::startTrans();
        if (!model('User')->buy($data['money'])) {
            return input('param.money');
            return json(['re' => false, 'info' => '您的余额不足！']);
        }
        $re = ['re' => model('Bought')->allowField(true)->save($data), 'info' => '操作失败，系统故障'];
        if ($re['re']) {
            $re['re'] = model('User')->find($data['admin_id'])->setInc('money', $data['money']);
            if (!$re['re']) {
                Db::rollback();
            } else {
                model('Message')->saveMessage($data['admin_id'], $data['book_id']);
                Db::commit();
            }
        } else {
            Db::rollback();
        }
        return json($re);
    }
    public function adminBuy() //后台界面实行的购买操作
    {
        $data = input('param.');
        Db::startTrans();
        if (model('User')->buy($data['money']) && model('User')->find($data['admin_id'])->setInc('money', $data['money']) && model('Bought')->allowField(true)->save($data)) {
            model('Message')->saveMessage($data['admin_id'], $data['book_id']);
            Db::commit();
            $this->redirect(url('admin/index/index'));
        } else {
            Db::rollback();
            $this->error('操作失败，系统故障');
        }
    }
}
