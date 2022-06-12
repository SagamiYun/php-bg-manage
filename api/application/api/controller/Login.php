<?php

namespace app\api\controller;

use app\common\model\AdminModel;
use think\facade\Env;
use think\Request;
use Firebase\JWT\JWT;

class Login extends Cross
{
    public function login(Request $request){
        $username = $request->param('username');
        $password = $request->param('password');
        $admin = new AdminModel();
        $info = $admin->where('username', $username)->find();
        if(!$info){
            return error('账号或密码错误');
        }
        if($info['password']!=md5($password)){
            return error('账号或密码错误');
        }


        //JWT鉴权
        $jwt = new JWT();
        $key = Env::get('token_key');
        $payload = [
            'iss' => Env::get('local_url'),
            'aud' => Env::get('local_url'),
            'iat' => time(),
            'nbf' => time(),
            'aid' => $info['id']
        ];

        db('admin')->where('id',$info['id'])->update([
            'status' => 1,
            'update_time'=>time()
        ]);

        $token = $jwt::encode($payload,$key,'HS256');

        //获取路由权限
        $urInfo = db('user_role')->where('user_id', $info['id'])->find();
        $rpInfo = db('role_permission')->alias('rp')
            ->where('role_id',$urInfo['role_id'])
            ->join('permission p', 'rp.permission_id = p.id')
            ->select();


        return json(['code'=>1,'msg'=>'登陆成功','token'=>$token,'permission'=>$rpInfo,'user'=>['username'=>$info['username'],
            'nick_name'=>$info['nick_name'],'age'=>$info['age'],'sex'=>$info['sex'],
            'address'=>$info['address'],'avatar'=>$info['avatar']]]);
    }

}
