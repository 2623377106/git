<?php

namespace app\login\controller;

use app\login\model\Auth;
use app\login\model\Ru;
use think\Controller;
use think\Request;

class Role extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
//        显示角色列表页面的方法
        $auth=Auth::select();
        $data=\app\login\model\Role::select();
        return view('role',['data'=>$data,'auth'=>$auth]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //显示角色添加页面
        return view('roleadd');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
        //接收参数
        $param=input();
        //        验证参数
        $rule = [
            'role_name'  =>  'require',
            'sort' =>  'require',
        ];
        $result = $this->validate($param,$rule);
        if(true !== $result){
            // 验证失败 输出错误信息
            $this->error($result);
        }
        $data=\app\login\model\Role::create($param,true);
        if($data){
            return redirect('index');
        }
    }
    public function ru(){
//        接收分配的权限
        $param=input();
        //        验证参数
        $rule = [
            'rol_id'  =>  'require',
            'aut_id' =>  'require',
        ];
        $result = $this->validate($param,$rule);
        if(true !== $result){
            // 验证失败 输出错误信息
            $this->error($result);
        }
        unset($param['/login/role/ru_html']);
        $data=Ru::update($param,['rol_id'=>$param['rol_id']]);
        if($data){
            return redirect('index');
        }
    }
}
