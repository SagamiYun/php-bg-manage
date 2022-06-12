<?php

namespace app\api\controller;

use think\Db;
use think\Request;

class User extends Base
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

        $userlist = Db::table('admin')->alias('a')
            ->where('username','like',"%".$search."%")
            ->join('user_role ur', 'a.id = ur.user_id')
            ->join('role r', 'ur.role_id = r.id')
            ->limit($ps)
            ->page($pn)
            ->field('a.id,a.username,a.nick_name,a.age,a.sex,a.address,ur.role_id,r.name,r.comment')
        ->select();
        $role = 1;
        $conunt = db('admin')->count('id');

        return json(['userlist'=>$userlist,'conunt'=>$conunt]);
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
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $id = $request->param('id');
        $username = $request->param('username');
        $nick_name = $request->param('nick_name');
        $age = $request->param('age');
        $sex = $request->param('sex');
        $address = $request->param('address');

        if(!isset($username) || empty($username)){
            return error('请至少指定一个用户名');
        }

        //先用max方法获取当前最大的id，然后加1，保存为变量
        $max_id = DB::name('admin') ->max('id');
        //id+1
        $max_id++;
        //重置自动增加为当前最大值加1
        DB::execute("alter table admin auto_increment=".$max_id);

        $insertId = db('admin')->insertGetId([
            'username' => $username,
            'nick_name' => $nick_name,
            'password' => md5(123456),
            'age' => $age,
            'sex' => $sex,
            'status' => 0,
            'address' => $address,
            'update_time'=>time(),
            'create_time'=>time()
        ]);

        $insert = Db::table('user_role')->insert([
            'user_id' => $insertId,
            'role_id' => 2
        ]);

        if($insert!=1){
            return error('新增失败');
        }

        return success('新增成功');

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
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $id = $request->param('id');
        $username = $request->param('username');
        $nick_name = $request->param('nick_name');
        $age = $request->param('age');
        $sex = $request->param('sex');
        $address = $request->param('address');

        db('admin')->where('id',$id)->update([
            'username' => $username,
            'nick_name' => $nick_name,
            'age' => $age,
            'sex' => $sex,
            'address' => $address,
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
        db('admin')->where('id', $id)->delete();
        db('user_role')->where('user_id', $id)->delete();
        return success('删除成功');
    }
}
