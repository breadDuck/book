<?php

namespace app\admin\controller;

use app\common\controller\AdminBase;

class User extends AdminBase
{
    public function list()
    {
        $userList = model('User')->select();
        $this->assign('userList', $userList);
        return $this->fetch();
    }
    public function edit()
    {
        $id = input('param.id');
        $user = model('User')->get($id);
        $this->assign('user', $user);
        return $this->fetch();
    }
    public function doEdit()
    {
        $data = input('param.');
        if (!$data['password']) {
            unset($data['password']);
        }
        $re = model('User')->update($data);
        if ($re) {
            session('bookuser', model('User')->get($data['id'])); //立即修改session中的信息
            $this->success('修改成功', url('admin/user/list'));
        } else {
            $this->success('修改失败', url('admin/user/list'));
        }
    }
    public function delete()
    {
        $id = input('param.id');
        $re = model('User')->destroy($id);
        if ($re) {
            $this->success('删除成功', url('admin/user/list'));
        } else {
            $this->success('删除失败', url('admin/user/list'));
        }
    }
    public function add()
    {
        return $this->fetch();
    }
    public function doAdd()
    {
        $data = input('param.');
        $v = validate('User');
        if (!$v->check($data)) {
            $this->error($v->getError());
        }
        $re = model('User')->save($data);
        if ($re) {
            $this->success('添加成功', url('admin/user/list'));
        } else {
            $this->success('添加失败', url('admin/user/list'));
        }
    }
    public function editName()
    {
        return view();
    }
    public function doEditName()
    {
        $data = input('post.');
        $re = model('User')->isUpdate(true)->save($data);
        if ($re) {
            session('bookuser')['name'] = $data['name']; //立即修改session中的内容
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！');
        }
    }
    public  function editPass()
    {
        return view();
    }
    public function doEditPass()
    {
        $data = input('post.');
        $newMd5Pass = md5($data['oldPass']);
        if ($newMd5Pass != session('bookuser')['password']) {
            $this->error('修改失败！密码输入错误！');
        }
        $v = validate('EditPass');
        if (!$v->check($data)) {
            $this->error($v->getError());
        }
        $re = model('User')->allowField(true)->isUpdate(true)->save($data);
        if ($re) {
            session('bookuser')['password'] = $newMd5Pass; //立即修改session中的内容
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！');
        }
    }
}
