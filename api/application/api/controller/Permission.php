<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

class Permission extends Cross
{
    public function permission(){
        $permission = permission();
        return json(['permission'=>$permission]);
    }
}
