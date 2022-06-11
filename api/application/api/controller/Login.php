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
            'status' => 1
        ]);

        $token = $jwt::encode($payload,$key,'HS256');

        return json(['code'=>1,'msg'=>'登陆成功','token'=>$token]);
    }

}
