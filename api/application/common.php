<?php

    function error($msg = '', $status = 0): \think\response\Json
    {
        if($status = 1){
            return json(['code'=>0,'msg'=>$msg])-> send();
        }
        return json(['code'=>0,'msg'=>$msg]);
    }

    function success($msg = ''): \think\response\Json
    {
        return json(['code'=>1,'msg'=>$msg]);
    }



