<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

class Logout extends Base
{
    /**
     * 登出方法
     *
     * @return \think\Response
     */
    public function index()
    {
        $update = db('admin')->where('id', $this->aid)->update([
            'status' => 0,
            'update_time'=>time()
        ]);

        if($update==0){
            return error('登出失败');
        }

        return success('登出成功');

    }

}
