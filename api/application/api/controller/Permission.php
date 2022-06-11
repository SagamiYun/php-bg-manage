<?php

namespace app\api\controller;

class Permission extends Cross
{
    public function permission(){
        $permission = new \app\common\model\PermissionModel();
        return json(['permission'=>$permission -> select()]);
    }
}
