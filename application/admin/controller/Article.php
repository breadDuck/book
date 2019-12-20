<?php

namespace app\admin\controller;

use app\common\controller\AdminBase;


class Article extends AdminBase
{
    // public function _initialize() //相当于构造函数
    // {
    //     if (!session('?bookuser')) {
    //         $this->error('您尚未登录，请先登录', url('index/user/login'));
    //     }
    // }

    public function list($where = [])
    {
        $date = input('param.date');
        if (is_numeric($date)) {
            $where['created_at'] = [
                'between', [$date, $date + 86400]
            ];
        }
        $id = input('param.id');
        if (is_numeric($id)) {
            $where['id'] = $id;
        }
        $is_upload = input('param.is_upload');
        if (is_numeric($is_upload)) {
            $where['is_upload'] = $is_upload;
        }
        $category_id = input('param.category_id');
        if (is_numeric($category_id)) {
            $where['category_id'] = $category_id;
        }
        $title = input('param.title');
        if ($title) {
            $where['title'] = ['like', '%' . $title . '%'];
        }

        $bookList = model('Book')->where($where)->paginate(8, false, ['query' => input('get.')]);
        $categoryList = model('Category')->all();
        $this->assign([
            'bookList' => $bookList,
            'categoryList' => $categoryList
        ]);
        return $this->fetch();
    }
    public function selfList() //查看自己发表的书目，也用paginate方法渲染
    {
        $id = session('bookuser.id');
        $bookList = model('Book')->where('admin_id', $id)->paginate(8);
        $categoryList=[]; //防止报错...
        $this->assign([
            'bookList' => $bookList,
            'categoryList' => $categoryList
        ]);
        return $this->fetch('list');
    }
    public function delete()
    {
        $id = input('param.id');
        $re = model('Book')->destroy($id);
        if ($re) {
            $this->success('删除成功', url('admin/article/list'));
        } else {
            $this->success('删除失败', url('admin/article/list'));
        }
    }
    public function edit()
    {
        $id = input('param.id');
        $book = model('Book')->get($id);
        $categoryList = model('Category')->all();
        $this->assign([
            'book' => $book,
            'categoryList' => $categoryList
        ]);
        return $this->fetch();
    }
    public function doEdit()
    {
        $data = input('param.');
        $data['pic'] = $data['pic'] ?: 'https://www.baidu.com/img/bd_logo1.png?where=super'; //暂时修改pic字段，以通过格式验证
        $v = validate('Book');
        if (!$v->check($data)) {
            $this->error($v->getError());
        }
        // echo '<pre>';
        // print_r($data);
        // die;
        $id = $data['id'];
        if ($data['pic_type'] != 'none') { //如果修改了图片
            if ($data['pic_type'] == 'upload') { //图片为上传文件方式
                $data['is_upload'] = 1;
                $file = request()->file('pic');
                if ($file) {
                    $path = ROOT_PATH . 'static/upload';
                    $info = $file->validate([  //加一些上传文件的格式验证,下面的getError()会动态获取不通过的信息
                        'ext' => 'jpg,jpeg,png,gif',
                        'size' => 2100000
                    ])->move($path);
                    if ($info) {
                        $data['pic'] = $info->getSaveName(); //图片上传成功
                        if (!$data['intro']) { //如果简介里没写东西，就删除这一字段
                            unset($data['intro']);
                        }
                        model('Book')->allowField(true)->isUpdate(true)->save($data); //修改书的信息                
                        $this->success('修改成功！', url('admin/article/list'));
                    } else {
                        $this->error($file->getError());
                    }
                } else {
                    $this->error('未检测到文件，请确保上传图片！');
                }
            } else { //图片为写入URL地址方式
                if ($data['pic'] == 'https://www.baidu.com/img/bd_logo1.png?where=super') { //检测图片链接地址
                    $this->error('图片的链接地址不能为空');
                }
                $data['is_upload'] = 0;
                if (!$data['intro']) {
                    unset($data['intro']);
                }
                model('Book')->allowField(true)->isUpdate(true)->save($data); //修改书的信息
                $this->success('修改成功', url('admin/article/list'));
            }
        } //如果没有修改图片
        else {
            unset($data['pic']);
            unset($data['pic_type']);
            model('Book')->allowField(true)->update($data); //修改书的信息
            $this->success('修改成功！', url('admin/article/list'));
        }
    }

    public function add()
    {
        $categoryList = model('Category')->all();
        $this->assign('categoryList', $categoryList);
        return $this->fetch();
    }
    public function doAdd()
    {
        $data = input('param.');
        // echo '<pre>';
        // print_r($data);
        // die;
        if ($data['pic_type'] == 'upload') { //图片为上传文件方式
            $data['pic'] = 'https://www.baidu.com/img/bd_logo1.png?where=super'; //暂时修改pic字段，以通过格式验证
            $v = validate('Book');
            if (!$v->check($data)) {
                $this->error($v->getError());
            }
            $data['is_upload'] = 1;
            $file = request()->file('pic');
            if ($file) {
                $path = ROOT_PATH . 'static/upload';
                $info = $file->validate([  //加一些上传文件的格式验证,下面的getError()会动态获取不通过的信息
                    'ext' => 'jpg,jpeg,png,gif',
                    'size' => 2100000
                ])->move($path);
                if ($info) {
                    $data['pic'] = $info->getSaveName(); //图片上传成功
                    if (!$data['intro']) { //如果简介里没写东西，就删除这一字段
                        unset($data['intro']);
                    }
                    model('Book')->allowField(true)->save($data); //存入书的信息                
                    $this->success('发表成功！', url('admin/article/list'));
                } else {
                    $this->error($file->getError());
                }
            } else {
                $this->error('未检测到文件，请确保上传图片！');
            }
        } else { //图片为写入URL地址方式
            $data['is_upload'] = 0;
            if (!$data['intro']) {
                unset($data['intro']);
            }
            $v = validate('Book');
            if (!$v->check($data)) {
                $this->error($v->getError());
            }
            model('Book')->allowField(true)->save($data); //存入书的信息
            $this->success('发表成功！', url('admin/article/list'));
        }
    }
}
