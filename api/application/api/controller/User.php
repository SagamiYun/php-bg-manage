<?php

namespace app\api\controller;

use app\common\model\AdminModel;
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

        $userList = Db::table('admin')->alias('a')
            ->where('username','like',"%".$search."%")
            ->join('user_role ur', 'a.id = ur.user_id')
            ->join('role r', 'ur.role_id = r.id')
            ->limit($ps)
            ->page($pn)
            ->field('a.id,a.username,a.nick_name,a.age,a.sex,a.address,ur.role_id,r.name,r.comment')
            ->select();

        foreach ($userList as $key=> $v){
            $roleInfo = db('user_role')->where('user_id', $v['id'])->column('role_id');
            $v['roles'] = $roleInfo;
            $userList[$key] = $v;
        }

        $conunt = db('admin')->count('id');

        return json(['userlist'=>$userList,'conunt'=>$conunt]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function count()
    {
        $count = DB::query("select count(id) count, address from admin GROUP BY address");
        return json(['data'=>$count]);
    }

    /**
     * 创建新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function create(Request $request)
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

        $admin = new AdminModel();
        $info = $admin->where('username', $username)->find();
        if($info){
            return error('请指定唯一用户名');
        }

        //重置自动增加为当前最大值加1
        $a_max_id = DB::name('admin') ->max('id');
        $ur_max_id = DB::name('user_role') ->max('user_id');
        $a_max_id++;
        $ur_max_id++;
        DB::execute("alter table admin auto_increment=".$a_max_id);
        DB::execute("alter table user_role auto_increment=".$ur_max_id);

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
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $id = $request->param('id');
        $role_id= $request->param('roles');

        db('user_role')->where('user_id',$id)->delete();

        foreach ($role_id as $key){
            db('user_role')->insert([
                'user_id' => $id,
                'role_id'=> $key
            ]);
        }

        if($id==$this->aid){
            return json(['code'=>1,'boole'=>true]);
        }

        return json(['code'=>1,'msg'=>'更新成功']);
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
            'update_time'=>time()
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
