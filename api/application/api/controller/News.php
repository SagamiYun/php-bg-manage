<?php

namespace app\api\controller;

use think\Db;
use think\Request;

class News extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $pageNum = $request->param('pageNum')?$request->param('pageNum'):1;
        $pageSize = $request->param('pageSize')?$request->param('pageSize'):10;
        $search = $request->param('search')?$request->param('search'):'';

        $pn = (integer)$pageNum;
        $ps = (integer)$pageSize;

        $newsList = Db::table('news')
            ->where('title','like',"%".$search."%")
            ->limit($ps)
            ->page($pn)
            ->select();
        $conunt = db('news')->count('id');

        return json(['records'=>$newsList,'conunt'=>$conunt]);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $title = $request->param('title');
        $content = $request->param('content');
        $adminInfo = db('admin')->where('id', $this->aid)->find();

        if(!isset($title) || empty($title)){
            return error('请指定一个标题');
        }

        //重置自动增加为当前最大值加1
        $img_max_id = DB::name('news') ->max('id');
        $img_max_id++;
        DB::execute("alter table news auto_increment=".$img_max_id);

        db('news')->insert([
            'title' => $title,
            'content' => $content,
            'author' => $adminInfo['username'],
            'time'=> date('Y-m-d H:i:s',time())
        ]);

        return  success('更新成功');
    }


    /**
     * 保存更新的资源
     *
     * @param Request $requestd
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $id = $request->param('id');
        $title = $request->param('title');
        $content = $request->param('content');
        $author = $request->param('author');

        db('news')->where('id',$id)->update([
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'time'=> date('Y-m-d H:i:s',time())
        ]);

        return  success('更新成功');
    }


    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        db('news')->where('id', $id)->delete();
        return success('删除成功');
    }
}
