<?php

namespace app\admin\validate;

use think\Validate;

class Book extends Validate
{
    // protected $regex='/\\xF0\\x9F\\x98\\x84/U';
    protected $rule = [
        'title' => 'require|max:40',
        'book_key' => 'require|max:40',
        'pic' => 'require|max:250',
        'intro'=>'max:600',
        'money'=>'require|between:1,100'
    ];
    protected $message = [
        'title.require' => '书名不能为空',
        'title.max' => '书名长度须在40字以内',
        'book_key.require' => '书籍的密钥不能为空',
        'book_key.max' => '书籍的密钥过长！(40位以内)',
        'pic.require' => '图片的链接地址不能为空',
        'pic.max' => '图片的链接地址过长！(250位以内)',
        'intro.max'=>'简介字数过长(限600字以内)',
        'money.require'=> '书的价格不能为空',
        'money.between'=> '书的价格须在1-100金币之间'
    ];
}
