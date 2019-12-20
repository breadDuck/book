<?php

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\index\model\Bought;
use app\index\model\Collect;
use think\Request;

class Index extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $id = session('bookuser')['id'];
        $collectList =Collect::where('admin_id', '=', $id)->paginate(8); //获取用户收藏的书列表
        $boughtList=Bought::where('admin_id', '=', $id)->select(); //获取用户已购书籍列表
        $bought=new Bought();
        foreach ($collectList as $v) { //为收藏列表的每本书验证一下是否已购买
            $re=$bought->verifyBought($v->book_id,$id);
            $v['bought']=$re;
        }
        $user = model('User')->get($id); //获取用户所有信息
        $this->assign([
            'collectList' => $collectList,
            'boughtList'=>$boughtList,
            'user' => $user
        ]);
        return $this->fetch();
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
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
