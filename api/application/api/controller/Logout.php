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
        $aid = $this->aid;
        $update = db('admin')->where('id', $aid)->update([
            'status' => 0
        ]);

        if($update==0){
            return error('登出失败');
        }

        return success('登出成功');

    }

}
