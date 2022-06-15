<?php

namespace app\api\controller;

use think\Request;

class Admin extends Base
{
    /**
     * 查询用户信息
     *
     * @return \think\Response
     */
    public function index()
    {
        $adminList =db('admin')->where('id', $this->aid)->find();
        //dump($adminList);
        //die;
        return json(['code'=>1,'msg'=>'成功','data'=>['username'=>$adminList['username'],
            'nick_name'=>$adminList['nick_name'],'age'=>$adminList['age'],'sex'=>$adminList['sex'],
            'address'=>$adminList['address'],'avatar'=>$adminList['avatar']]]);
    }

    /**
     * 上传头像
     */
    public function uploadImg(Request $request)
    {
        $file = $request->file('file');

        $url = './static/upload/avatar/';

        if(!file_exists($url)){
           mkdir($url,0777,true);
        }

        $info = $file->move($url);

        if($info){
            $imgUrl = $info->getSaveName();

            db('admin')->where('id',$this->aid)->update([
                'avatar' => 'http://www.bgmanag.io/static/upload/avatar/'.$imgUrl
            ]);

            $adminInfo = db('admin')->where('id', $this->aid)->find();
            db('img')->insert([
                'author' => $adminInfo['username'],
                'img_url' =>  'http://www.bgmanag.io/static/upload/avatar/'.$imgUrl,
                'src_url' =>  './static/upload/avatar/'.$imgUrl,
                'name' => 'avatar'.time(),
                'update_time' => date('Y-m-d H:i:s',time())
            ]);

            return json(['code'=>1,'msg'=>'上传成功','imgurl'=>'http://www.bgmanag.io/static/upload/avatar/'.$imgUrl]);
        }else{
            return error('上传失败');
        }
    }


    /**
     * 显示指定的资源
     *
     * @return \think\Response
     */
    public function updateInfo()
    {
        $adminInfo = db('admin')->where('id', $this->aid)->find();
        return json(['code'=>1,'msg'=>'登陆成功','user'=>['id'=>$this->aid,'username'=>$adminInfo['username'],
            'nick_name'=>$adminInfo['nick_name'],'age'=>$adminInfo['age'],'sex'=>$adminInfo['sex'],
            'address'=>$adminInfo['address'],'avatar'=>$adminInfo['avatar']]]);
    }

    /**
     * 更新密码
     *
     * @return \think\Response
     */
    public function updatePassword(Request $request)
    {
        $password = $request->param('password');
        $newPass = $request->param('newPass');
        $sqlPassword = db('admin')->where('id', $this->aid)->find();
        if(md5($password)!=$sqlPassword['password']){
            return error('原密码错误');
        }

        db('admin')->where('id',$this->aid)->update([
            'password' => md5($newPass)
        ]);

        return success('更新成功');
    }

    /**
     * 保存更新的用户信息
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $username = $request->param('username');
        $nick_name = $request->param('nick_name');
        $age = $request->param('age');
        $sex = $request->param('sex');
        $address = $request->param('address');
        $avatar = $request->param('avatar');

        db('admin')->where('id',$this->aid)->update([
            'username' => $username,
            'nick_name' => $nick_name,
            'age' => $age,
            'sex' => $sex,
            'address' => $address,
            'avatar' => $avatar,
            'update_time'=>time()
        ]);

        return  success('更新成功');
    }
}
