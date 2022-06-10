<?php
    function permission () {
        $permission = new \app\common\model\PermissionModel();
        return $permission -> select();
    }

    function error($msg = '', $status = 0): \think\response\Json
    {
        if($status = 1){
            return json(['code'=>0,'msg'=>$msg])-> send();
        }
        return json(['code'=>0,'msg'=>$msg]);
    }



    //function success($msg = '' , $status = 0 , $data = null, $token = ''): \think\response\Json
    //{
    //    if($status = 1){
    //        return json(['code'=>1,'data'=>$data]);
    //    }
    //    if($status = 2){
    //        return json(['code'=>1,'msg'=>$msg,'token'=>$token]);
    //    }
    //    return json(['code'=>1,'msg'=>$msg]);
    //}
    function success($msg = ''): \think\response\Json
    {
        return json(['code'=>1,'msg'=>$msg]);
    }



