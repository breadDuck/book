<?php

namespace app\admin\controller;

use app\common\controller\AdminBase;

class Category extends AdminBase
{
    public function list()
    {
        $categoryList = model('Category')->all();
        $this->assign('categoryList', $categoryList);
        return $this->fetch();
    }
    public function edit()
    {
        $id = input('param.id');
        $category = model('Category')->get($id);
        $this->assign('category', $category);
        return $this->fetch();
    }
    public function doEdit()
    {
        $data = input('param.');
        $re = model('Category')->update($data);
        if ($re) {
            $this->success('修改成功', url('admin/category/list'));
        } else {
            $this->success('修改失败', url('admin/category/list'));
        }
    }
    public function delete()
    {
        $id = input('param.id');
        $re = model('Category')->destroy($id);
        if ($re) {
            $this->success('删除成功', url('admin/category/list'));
        } else {
            $this->success('删除失败', url('admin/category/list'));
        }
    }
    public function add()
    {
        return $this->fetch();
    }
    public function doAdd()
    {
        $data = input('param.');
        $re = model('Category')->save($data);
        if ($re) {
            $this->success('添加成功', url('admin/category/list'));
        } else {
            $this->success('添加失败', url('admin/category/list'));
        }
    }
}
