<?php
namespace app\login\controller;

use app\login\model\Admin;
use app\login\model\Auth;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
//        显示管理员登录的视图
        return view('index');
    }
//    完成用户登录
    public function login(){
//        接收参数
        $param=input();
//        验证参数
        $rule = [
            'admin_name'  =>  'require',
            'password' =>  'require',
        ];
        $result = $this->validate($param,$rule);
        if(true !== $result){
            // 验证失败 输出错误信息
           $this->error($result);
        }
        $data=Admin::where('admin_name',$param['admin_name'])
            ->where('password',md5($param['password']))
            ->find();
        if($data){
//            登录成功使用session记住用户id
            session('id',$data['admin_id']);
            return redirect('auth');
        }else{
            return $this->error("账户名或密码错误");
        }
    }
//    节点权限的添加和管理
    public function auth(){
//        查出当前用户对应的权限
        $id=session('id');
        $data=Admin::join('a_r','admin_id=admi_id')
            ->join('role','role_id=a_r.rol_id')
            ->join('r_u','role_id=r_u.rol_id')
            ->join('auth','auth_id=aut_id')
            ->where('admin_id',$id)
            ->select()->toArray();
//        调用递归函数
        $data=recursion($data);
       return view('auth',['data'=>$data]);
    }
//    退出登录功能方法
    public function logindel(){
        session('id',null);
        return redirect('index');
    }
//    权限添加方法
    public function authadd(){
        $param=input();
        $rule = [
            'auth_name'  =>  'require',
        ];
        $result = $this->validate($param,$rule);
        if(true !== $result){
            // 验证失败 输出错误信息
            $this->error($result);
        }
        $data=Auth::create($param,true);
        if($data){
          return  redirect('auth');
        }
    }
}
