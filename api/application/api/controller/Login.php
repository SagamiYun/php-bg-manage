<?php

namespace app\api\controller;

use app\common\model\AdminModel;
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
            return json(['code'=>0,'msg'=>'账号或密码错误']);
        }
        if($info['password']!=md5($password)){
            return json(['code'=>0,'msg'=>'账号或密码错误']);
        }


        //JWT鉴权
        $jwt = new JWT();
        $key = 'api123456';
        $payload = [
            'iss' => 'http://www.bgmanag.io',
            'aud' => 'http://www.bgmanag.io',
            'iat' => time(),
            'nbf' => time(),
            'aid' => $info['id']
        ];

        $token = $jwt::encode($payload,$key,'HS256');

        return json(['code'=>1,'msg'=>'登陆成功','token'=>$token]);
    }
}
