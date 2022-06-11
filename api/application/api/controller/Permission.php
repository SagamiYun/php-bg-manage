<?php

namespace app\api\controller;

use think\Request;

class Permission extends Cross
{
    /**
     * 动态路由权限接口
     * 暂时废用
    */
    public function permission(Request $request){
        //$aid = $request->param('aid');
        //$collection = db('admin')->alias('a')
        //    ->join('role_permission rp', 'p.id = rp.permission_id')
        //    ->join('role r', 'r.id = rp.role_id')
        //    ->join('user_role ur','ur.role_id = r.id')
        //    ->join('admin a','a.id = ur.user_id')
        //    ->where('aid',$aid)
        //    ->select();

        //$collection = db('admin')->alias('a')
        //    ->join('user_role ur', 'a.id = ur.user_id')
        //    ->join('role r', 'ur.role_id = r.id')
        //    ->join('role_permission rp','rp.role_id = r.id')
        //    ->join('permission p','p.id = rp.permission_id')
        //    ->field('p.name,p.path,p.comment,p.icon')
        //    ->paginate(10);

        //$admin = AdminModel::get(1);
        //$username = $admin->username;
        //echo $username;
        //foreach ($username as $uame) {
        //    // 输出用户的角色名
        //    echo $uame->id;
        //}
        //$info['id'] = 1;
        //$query = db('admin')->where('id', $info['id'])->find();
        //$collection = db('user_role')->where('user_id', $query['id'])->find();
        //
        //$collection2 = db('role_permission')->alias('rp')
        //    ->where('role_id',$collection['role_id'])
        //    ->join('permission p', 'rp.permission_id = p.id')
        //    ->select();
        //
        //return json(['permission'=>$collection2]);
    }
}
