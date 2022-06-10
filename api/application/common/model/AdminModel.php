<?php

namespace app\common\model;

use think\Model;

class AdminModel extends Model
{
    /**
     * @var mixed
     */
    public $create_time;
    /**
     * @var mixed
     */
    public $update_time;

    protected $table = 'admin';
}
