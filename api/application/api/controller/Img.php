<?php

namespace app\api\controller;

use think\Db;
use think\Request;

class Img extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param Request $request
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $url = './static/upload/editor/';

        if(!file_exists($url)){
            mkdir($url,0777,true);
        }
        $info = $file->move($url);

        if($info){
            $imgUrl = $info->getSaveName();

            $adminInfo = db('admin')->where('id', $this->aid)->find();

            //重置自动增加为当前最大值加1
            $img_max_id = DB::name('img') ->max('id');
            $img_max_id++;
            DB::execute("alter table img auto_increment=".$img_max_id);

            db('img')->insert([
                'author' => $adminInfo['username'],
                'img_url' =>  'http://www.bgmanag.io/static/upload/editor/'.$imgUrl,
                'src_url' =>  './static/upload/editor/'.$imgUrl,
                'update_time' => date('Y-m-d H:i:s',time())
            ]);

            return json(['errno'=>0,'msg'=>'上传成功','data'=>['url'=>'http://www.bgmanag.io/static/upload/editor/'.$imgUrl]]);
        }else{
            return json(['errno'=>0,'message'=>'上传失败']);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
