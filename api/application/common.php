<?php
    function permission () {
        $permission = new \app\common\model\PermissionModel();
        return $permission -> select();
    }

    function error(String $msg): \think\response\Json
    {
        return json(['code'=>0,'msg'=>$msg]);
    }


