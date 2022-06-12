<?php

namespace app\api\controller;

use app\common\model\RoleModel;
use think\Controller;
use think\Db;
use think\Request;

class Role extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
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
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $comment = $request->param('comment');
        $id = $request->param('id');

        $roleModel = new RoleModel();
        $role = $roleModel->where('comment',$comment)->find();

        $roleId = $role['id'];

        $updateInfo = db('user_role')
            ->where('user_id', $id)
            ->update([
            'role_id' => $roleId
        ]);

        if($this->aid==$id){
            if($updateInfo!=0){
                return json(['code'=>2,'msg'=>'更改权限成功,跳转到首页重新登录']);
            }else {
                return error('您的权限没有更改');
            }
        }

        if($updateInfo==0){
            return error('该用户权限暂未修改');
        }

        return success('更新成功');
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
