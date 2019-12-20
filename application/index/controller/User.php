<?php

namespace app\index\controller;

use app\common\controller\IndexBase;

class User extends IndexBase
{
    public function login()
    {
        return $this->fetch();
    }
    public function doLogin()
    {
        $data = input('param.');
        $user = model('User')->getLogin($data['name'], $data['password']);
        if (!captcha_check($data['captcha'])) {
            $this->error('登录失败，验证码错误');
        } elseif (!$user) {
            $this->error('登录失败，用户信息输入有误');
        } else {
            session('bookuser', $user);
            $mId = model('Message')->getNewMessageId();
            if ($user['message_id'] < $mId) {
                session('new', 1);
            } else {
                session('new', 0);
            }
            model('Message')->setReadMessage();
            $this->success('登录成功！', url('index/index/index'));
        }
    }
    public function logout()
    {
        session('bookuser', null);
        // $this->success('账号已退出！', url('index/user/login'));
        $url = $_SERVER['HTTP_REFERER'];
        header("location:$url"); //刷新当前页面
    }
    public function register()
    {
        return view();
    }
    public function doRegister()
    {
        $data = input('param.');
        $nameTest = model('User')->where('name', $data['name'])->find();
        $emailTest = model('User')->where('email', '=', $data['email'])->find();
        if (!captcha_check($data['captcha'])) {
            $this->error('验证码填写错误！');
        } elseif ($nameTest) {
            $this->error('注册失败，此用户名已存在');
        } elseif ($emailTest) {
            $this->error('注册失败，此邮箱已注册过用户');
        } else {
            model('User')->allowField(true)->save($data);
            $this->success('注册成功!', url('index/user/login'));
        }
    }
}
