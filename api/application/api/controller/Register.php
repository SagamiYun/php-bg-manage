<?php

namespace app\api\controller;

use app\common\model\AdminModel;
use think\Db;
use think\Request;

class Register extends Cross
{
    public function  register(Request $request){
        $username = $request->param('username');
        $password = $request->param('password');
        if((!isset($username) || empty($username)) && (!isset($password) || empty($password))){
            return error('请求错误');
        }

        $admin = new AdminModel();
        $info = $admin->where('username', $username)->find();
        if($info){
            return error('用户名已存在');
        }

        $insertId = Db::table('admin')->insertGetId([
            'username' => $username,
            'password' => md5($password),
            'status' => 0,
            'update_time'=>time(),
            'create_time'=>time()
        ]);

        $insert = Db::table('user_role')->insert([
            'user_id' => $insertId,
            'role_id' => 3
        ]);

        if($insert!=1){
            return error('注册失败');
        }

        return success('注册成功');
    }
}
