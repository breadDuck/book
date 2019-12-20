<?php
namespace app\index\controller;

use think\Controller;

class Comment extends Controller
{
    public function publish()
    {
        $data=input('param.');
        // echo '<pre>';
        // print_r($data);
        $re=model('Comment')->save($data);
        if ($re) {
           model('Book')->get($data['book_id'])->setInc('comment_times',1);
        }
        return $re;
    }
    public function show() 
    {
        $data=request()->param();
        $book_id=$data['book_id'];
        $start=$data['start'];
        $num=$data['num'];
        $arr=model('Comment')->where('book_id',$book_id)->order('favor_times','desc')->limit($start,$num)->select();
        return json_encode($arr);
    } 
    public function favor()
    {
        if (!session('?favor')) {
            session('favor',[]);
        }
        $id=input('param.id');
        if (in_array($id,session('favor'))) {
           return json_encode([
            're'=>'',
            'info'=>'不能重复点赞'
           ]);
        }
        $re=model('Comment')->get($id)->setInc('favor_times',1);
        if ($re) {
            $arr=session('favor');
            $arr[]=$id;
            session('favor',$arr);
            return json_encode([
                're'=>1
               ]);
        }else{
            return json_encode([
                're'=>'',
                'info'=>'操作失败'
               ]);
        }
    } 
}