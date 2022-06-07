<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

class Login extends Cross
{
    public function login(){
        return json(['code'=>0,'msg'=>'登陆成功']);
    }
}
