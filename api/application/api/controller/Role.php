<?php

namespace app\api\controller;

use think\Db;
use think\Request;

class Role extends Base
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

        $roleInfo = db('role')
            ->where('comment','like',"%".$search."%")
            ->limit($ps)
            ->page($pn)
            ->select();

        foreach ($roleInfo as $key=> $v){
            $permissionInfo = db('role_permission')->where('role_id', $v['id'])->column('permission_id');
            $v['permissions'] = $permissionInfo;
            $roleInfo[$key] = $v;
        }

        $conunt = db('role')->count('id');

        return  json(['records'=>$roleInfo,'conunt'=>$conunt]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        $name = $request->param('name');
        $comment = $request->param('comment');

        //重置自动增加为当前最大值加1
        $role_max_id = db('role') ->max('id');
        $role_max_id++;
        DB::execute("alter table role auto_increment=".$role_max_id);

        db('role')->insert([
            'name' => $name,
            'comment' => $comment
        ]);

        return success('新增成功');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $id = $request->param('id');
        $permissions = $request->param('permissions');

        db('role_permission')->where('role_id',$id)->delete();

        foreach ($permissions as $key){
            db('role_permission')->insert([
                'role_id' => $id,
                'permission_id'=> $key
            ]);
        }

        if($id==$this->aid){
            return json(['code'=>1,'boole'=>true]);
        }

        return json(['code'=>1,'msg'=>'更新成功']);
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
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $id = $request->param('id');
        $name = $request->param('name');
        $comment = $request->param('comment');

        db('role')->where('id',$id)->update([
            'name' => $name,
            'comment' => $comment
        ]);

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
        db('role')->where('id',$id)->delete();
        db('role_permission')->where('role_id',$id)->delete();
        return success('删除成功');
    }
}
