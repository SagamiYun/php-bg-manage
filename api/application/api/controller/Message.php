<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;

class Message extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $messageList = Db::table('message')->alias('m')
            ->join('admin a', 'm.username = a.username')
            ->field('m.id,a.nick_name,m.content,m.time,a.avatar')
            ->select();

        return  json(['item'=>$messageList]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $content = $request->param('content');

        $adminInfo = db('admin')->where('id', $this->aid)->find();

        db('message')->insert([
            'content' => $content,
            'username' => $adminInfo['username'],
            'time' => date('Y-m-d H:i:s',time())
        ]);

        return  success('评论成功');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        db('message')->where('id', $id)->delete();
        return success('删除成功');
    }
}
