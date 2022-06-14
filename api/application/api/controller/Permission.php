<?php

namespace app\api\controller;

use think\Db;
use think\Request;

class Permission extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $pageNum = $request->param('pageNum')?$request->param('pageNum'):1;
        $pageSize = $request->param('pageSize')?$request->param('pageSize'):10;
        $search = $request->param('search')?$request->param('search'):'';

        $pn = (integer)$pageNum;
        $ps = (integer)$pageSize;

        $permissionList = Db::table('permission')
            ->where('comment','like',"%".$search."%")
            ->limit($ps)
            ->page($pn)
            ->select();
        $conunt = db('permission')->count('id');

        return json(['records'=>$permissionList,'conunt'=>$conunt]);
    }


    /**
     * 获取所有权限列表
    */
    public function getAll()
    {
        return json(['pinfo'=>db('permission')->select()]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $name = $request->param('name');
        $path = $request->param('path');
        $comment = $request->param('comment');
        $icon = $request->param('icon');

        if(!isset($name) || empty($name)){
            return error('请指定路径名');
        }
        if(!isset($path) || empty($path)){
            return error('请指定路径');
        }
        if(!isset($comment) || empty($comment)){
            return error('请指定路径备注');
        }


        $nInfo = db('permission')->where('name', $name)->find();
        if($nInfo){
            return error('路径名冲突');
        }
        $pInfo = db('permission')->where('path',$path)->find();
        if($pInfo){
            return error('路径冲突');
        }

        //重置自动增加为当前最大值加1
        $p_max_id = DB::name('permission') ->max('id');
        $rp_max_id = DB::name('role_permission') ->max('role_id');
        $p_max_id++;
        $rp_max_id++;
        DB::execute("alter table permission auto_increment=".$p_max_id);
        DB::execute("alter table role_permission auto_increment=".$rp_max_id);

        $insertId = db('permission')->insertGetId([
            'name' => $name,
            'path' => $path,
            'comment' => $comment,
            'icon' => $icon
        ]);

        $insert = Db::table('role_permission')->insert([
            'permission_id' => $insertId,
            'role_id' => 1
        ]);

        if($insert!=1){
            return error('新增失败');
        }

        return success('新增成功');
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $id = $request->param('id');
        $name = $request->param('name');
        $path = $request->param('path');
        $comment = $request->param('comment');
        $icon = $request->param('icon');

        db('permission')->where('id',$id)->update([
            'name' => $name,
            'path' => $path,
            'comment' => $comment,
            'icon' => $icon,
        ]);

        return  success('更新成功');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        db('permission')->where('id', $id)->delete();
        db('role_permission')->where('permission_id', $id)->delete();
        return success('删除成功,路由将在您重新登录后生效');
    }
}
