<?php

namespace app\api\controller;

use think\Db;
use think\Request;

class Book extends Base
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

        $booksList = db('book')
            ->where('name','like',"%".$search."%")
            ->limit($ps)
            ->page($pn)
            ->select();
        $conunt = db('book')->count('id');

        return json(['records'=>$booksList,'conunt'=>$conunt]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        $name = $request->param('name');
        $author = $request->param('author');
        $createTime = $request->param('create_time');
        $cover = $request->param('cover');

        if(!isset($name) || empty($name)){
            return error('请指定一个名称');
        }

        if(!isset($author) || empty($author)){
            return error('请指定一个作者');
        }

        if(!$cover){
            $cover['url'] = 0;
        }

        //重置自动增加为当前最大值加1
        $img_max_id = DB::name('book') ->max('id');
        $img_max_id++;
        DB::execute("alter table book auto_increment=".$img_max_id);

        db('book')->insert([
            'name' => $name,
            'author' => $author,
            'create_time'=> $createTime,
            'cover' => $cover['url'],
            'user_id' => $this->aid
        ]);

        return  success('新增成功');
    }

    
    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $id = $request->param('id');
        $name = $request->param('name');
        $author = $request->param('author');
        $createTime = $request->param('create_time');
        $cover = $request->param('cover');


        db('book')->where('id',$id)->update([
            'name' => $name,
            'author' => $author,
            'create_time' => $createTime,
            'cover' => $cover['url'],
            'user_id' => $this->aid
        ]);

        return  success('更新成功');
    }


    /**
     * 删除指定资源
     *
     * @param  int  $id
     */
    public function delete($id)
    {
        db('book')->where('id', $id)->delete();
        return success('删除成功');
    }


    /**
     * 批量删除指定资源
     *
     * @return \think\Response
     */
    public function deleteBatch(Request $request){
        $params = input('param.');

        foreach ($params as $key){
            db('book')->where('id',$key)->delete();
        }

        return success('批量删除成功');
    }
}
